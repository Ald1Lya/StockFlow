<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\product as Product;
use App\Models\sale as Sale;
use App\Models\saledetail as SaleDetail;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controller untuk Transaksi POS (Point of Sale / Mesin Kasir)
 */
class PosController extends Controller
{
    /**
     * Tampilan Mesin Kasir (Katalog Produk + Keranjang Belanja + Modal Nota)
     */
    public function index()
    {
        // 1. Ambil semua data produk yang statusnya aktif beserta relasi kategorinya
        $rawProducts = Product::with('category')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // 2. Kelompokkan produk berdasarkan nama produk:
        // Tujuannya agar produk yang punya varian satuan berbeda (misal: "Paku" satuan Kg dan Dus)
        // bersatu dalam 1 Card di tampilan kasir, sehingga kasir tinggal klik tombol switcher satuannya.
        $groupedProducts = $rawProducts->groupBy('name')->map(function ($variants, $name) {
            $first = $variants->first();

            // Cari gambar dari salah satu varian yang memiliki file foto
            $imageProduct = $variants->first(fn ($v) => !empty($v->image));
            $imageUrl = $imageProduct && $imageProduct->image ? asset('storage/' . $imageProduct->image) : null;

            return [
                'name'          => $name,
                'image_url'     => $imageUrl,
                'category_id'   => $first->category_id,
                'category_name' => $first->category?->name ?? 'Umum',
                // Daftar varian (SKU, satuan/pcs, harga jual, dan sisa stok masing-masing)
                'variants'      => $variants->map(fn ($v) => [
                    'id'        => $v->id,
                    'sku'       => $v->sku,
                    'unit'      => $v->pcs,
                    'price'     => (int) $v->selling_price,
                    'stock'     => (int) $v->stock,
                    'image_url' => $v->image ? asset('storage/' . $v->image) : null,
                ])->values(),
            ];
        })->values();

        // 3. Ambil daftar kategori untuk dropdown filter di katalog kasir
        $categories = Category::orderBy('name')->get(['id', 'name']);

        // 4. Cek apakah ada ID transaksi terakhir di session (setelah berhasil bayar):
        // Jika ada, ambil datanya untuk langsung memunculkan pop-up modal struk/nota belanja
        $latestReceipt = session('last_sale_id')
            ? Sale::with(['user', 'details.product'])->find(session('last_sale_id'))
            : null;

        // 5. Render view POS
        return view('cashier.pos.index', [
            'groupedProducts' => $groupedProducts,
            'categories'      => $categories,
            'latestReceipt'   => $latestReceipt,
        ]);
    }

    /**
     * Proses Checkout / Pembayaran Transaksi Belanja
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari formulir kasir
        $data = $request->validate([
            'cash_tendered'      => ['required', 'numeric', 'min:0'],    // Nominal uang tunai pelanggan
            'items'              => ['required', 'array', 'min:1'],      // Daftar barang di keranjang belanja
            'items.*.product_id' => ['required', 'exists:products,id'],  // Pastikan ID produk valid ada di tabel products
            'items.*.quantity'   => ['required', 'integer', 'min:1'],    // Jumlah beli minimal 1
        ]);

        try {
            // 2. Gunakan Database Transaction agar jika ada 1 saja item gagal/stok kurang,
            // seluruh proses dibatalkan (rollback) sehingga data keuangan & stok tetap akurat.
            $sale = DB::transaction(function () use ($data) {
                $totalAmount = 0;
                $preparedItems = [];

                // 2a. Loop semua item untuk cek stok & hitung total belanja
                foreach ($data['items'] as $item) {
                    // lockForUpdate mengunci baris produk di DB sementara agar tidak bentrok dengan kasir lain
                    $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                    // Validasi kecukupan stok barang
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stok {$product->name} ({$product->pcs}) tidak cukup. Sisa: {$product->stock}.");
                    }

                    // Hitung subtotal tiap barang
                    $subtotal = $product->selling_price * $item['quantity'];
                    $totalAmount += $subtotal;

                    // Tampung sementara barang yang siap diproses
                    $preparedItems[] = [
                        'product'  => $product,
                        'quantity' => $item['quantity'],
                        'price'    => $product->selling_price,
                        'subtotal' => $subtotal,
                    ];
                }

                // 2b. Validasi uang tunai dari pembeli
                if ($data['cash_tendered'] < $totalAmount) {
                    throw new \Exception('Uang tunai kurang dari total tagihan.');
                }

                // 2c. Buat nomor invoice otomatis per hari (Contoh: INV-20261116-001)
                $todayPrefix   = 'INV-' . Carbon::now()->format('Ymd') . '-';
                $todayCount    = Sale::where('invoice_number', 'like', $todayPrefix . '%')->count() + 1;
                $invoiceNumber = $todayPrefix . str_pad($todayCount, 3, '0', STR_PAD_LEFT);

                // 2d. Simpan data induk transaksi ke tabel sales
                $sale = Sale::create([
                    'user_id'        => Auth::id(),                          // Kasir yang memproses
                    'invoice_number' => $invoiceNumber,
                    'total_amount'   => $totalAmount,
                    'cash_tendered'  => $data['cash_tendered'],
                    'change_amount'  => $data['cash_tendered'] - $totalAmount, // Hitung uang kembalian
                    'sale_date'      => now(),
                ]);

                // 2e. Simpan detail barang, kurangi stok produk, dan catat histori kartu stok
                foreach ($preparedItems as $item) {
                    // Simpan rincian item ke tabel saledetails
                    SaleDetail::create([
                        'sale_id'    => $sale->id,
                        'product_id' => $item['product']->id,
                        'quantity'   => $item['quantity'],
                        'unit_price' => $item['price'],
                        'subtotal'   => $item['subtotal'],
                    ]);

                    // Kurangi stok riil produk secara otomatis di tabel products
                    $item['product']->decrement('stock', $item['quantity']);

                    // Catat ke log histori mutasi stok (Stock Movements) tipe 'out' (barang keluar)
                    StockMovement::create([
                        'product_id'     => $item['product']->id,
                        'user_id'        => Auth::id(),
                        'type'           => 'out',
                        'quantity'       => $item['quantity'],
                        'reference_type' => 'sale',
                        'reference_id'   => $sale->id,
                        'reason'         => "Penjualan Kasir {$invoiceNumber}",
                        'status'         => 'approved',
                        'approved_by'    => Auth::id(),
                        'approved_at'    => now(),
                    ]);
                }

                return $sale;
            });

            // 3. Jika berhasil, redirect kembali ke halaman POS dengan pesan sukses
            // dan membawa last_sale_id agar otomatis membuka pop-up nota
            return redirect()
                ->route('cashier.pos.index')
                ->with('success', "Transaksi {$sale->invoice_number} berhasil!")
                ->with('last_sale_id', $sale->id);

        } catch (\Exception $e) {
            // Jika ada error (misal stok kurang atau uang kurang), kembalikan form dengan pesan error
            return back()->with('error', $e->getMessage());
        }
    }
}
