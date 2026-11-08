<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\product as Product;
use App\Models\purchase as Purchase;
use App\Models\purchasedetail as PurchaseDetail;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PurchaseController extends Controller
{
    // READ: Daftar semua PO dengan fitur pencarian.
    public function index(Request $request)
    {
        $search = $request->query('search');
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';

        $purchases = Purchase::query()
            ->with(['supplier', 'user'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('po_number', 'like', "%{$search}%")
                      ->orWhereHas('supplier', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.purchases.index', [
            'purchases' => $purchases,
            'search'    => $search,
        ]);
    }

    // CREATE: Form buat PO baru.
    // Route create ini sekaligus melayani request JSON dari JS untuk live-search produk,
    // sama persis dengan pola di ProductController — satu route, dua kegunaan.
    public function create(Request $request): View|JsonResponse
    {
        // JS di form mengirim Accept: application/json untuk mencari produk.
        if ($request->expectsJson()) {
            $validated = $request->validate([
                'search' => ['nullable', 'string', 'max:100'],
            ]);
            $search = trim($validated['search'] ?? '');

            $products = Product::query()
                ->where('is_active', true)
                ->when($search !== '', fn ($q) => $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                }))
                ->orderBy('name')
                ->limit(11)
                ->get(['id', 'name', 'sku', 'cost_price']);

            return response()->json([
                'data'     => $products->take(10)->values(),
                'has_more' => $products->count() > 10,
            ]);
        }

        $suppliers = Supplier::orderBy('name')->get(['id', 'name']);

        return view('admin.purchases.create', compact('suppliers'));
    }

    // STORE: Validasi, generate no. PO, simpan PO + detail item dalam satu transaksi.
    // Harga modal (unit_cost) hanya terlihat oleh admin — staf gudang tidak punya akses ke menu ini.
    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id'          => ['required', 'exists:suppliers,id'],
            'purchase_date'        => ['required', 'date'],
            'items'                => ['required', 'array', 'min:1'],
            'items.*.product_id'   => ['required', 'exists:products,id'],
            'items.*.quantity'     => ['required', 'integer', 'min:1'],
            'items.*.unit_cost'    => ['required', 'integer', 'min:0'],
        ]);

        // Generate no. PO dari tanggal: PO-MMDDYY
        // Contoh: 2026-11-28 → PO-112826
        // Kalau di hari yang sama sudah ada PO, tambah suffix: PO-112826-1, PO-112826-2, dst.
        $date     = Carbon::parse($data['purchase_date']);
        $base     = 'PO-' . $date->format('mdy');
        $poNumber = $base;
        $counter  = 1;
        while (Purchase::where('po_number', $poNumber)->exists()) {
            $poNumber = $base . '-' . $counter;
            $counter++;
        }

        // Total amount = jumlah (qty × harga beli) semua item.
        $totalAmount = collect($data['items'])->sum(
            fn ($item) => $item['quantity'] * $item['unit_cost']
        );

        // Simpan PO header + semua detail item dalam satu transaksi database.
        // Jika salah satu gagal, semuanya di-rollback.
        DB::transaction(function () use ($data, $poNumber, $totalAmount) {
            $purchase = Purchase::create([
                'supplier_id'   => $data['supplier_id'],
                'user_id'       => Auth::id(),
                'po_number'     => $poNumber,
                'status'        => 'pending',
                'total_amount'  => $totalAmount,
                'purchase_date' => $data['purchase_date'],
            ]);

            foreach ($data['items'] as $item) {
                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id'  => $item['product_id'],
                    'quantity'    => $item['quantity'],
                    'unit_cost'   => $item['unit_cost'],
                    'subtotal'    => $item['quantity'] * $item['unit_cost'],
                ]);
            }
        });

        return redirect()
            ->route('admin.purchases.index')
            ->with('success', "Purchase Order {$poNumber} berhasil dibuat.");
    }

    // UPDATE: Mengubah status PO.
    // Hanya staf gudang yang akan memakai ini (via dashboard gudang nanti).
    // Saat ini admin juga bisa mengubah status dari halaman index.
    //
    // Alur status:
    //   pending → received : stok produk BERTAMBAH sesuai qty detail PO
    //   received → pending : stok produk DIKURANGI kembali (batal terima)
    public function update(Request $request, Purchase $purchase)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'approved', 'received', 'cancelled'])],
        ]);

        $statusLama = $purchase->status;
        $statusBaru = $data['status'];

        DB::transaction(function () use ($purchase, $statusLama, $statusBaru) {
            $purchase->update(['status' => $statusBaru]);

            // Stok bertambah hanya sekali: saat status pertama kali menjadi received.
            if ($statusLama !== 'received' && $statusBaru === 'received') {
                foreach ($purchase->details as $detail) {
                    Product::where('id', $detail->product_id)
                        ->increment('stock', $detail->quantity);
                }
            }

            // Stok dikurangi kembali jika status di-reverse dari received.
            if ($statusLama === 'received' && $statusBaru !== 'received') {
                foreach ($purchase->details as $detail) {
                    Product::where('id', $detail->product_id)
                        ->decrement('stock', $detail->quantity);
                }
            }
        });

        return redirect()
            ->route('admin.purchases.index')
            ->with('success', "Status PO {$purchase->po_number} berhasil diperbarui.");
    }
}
