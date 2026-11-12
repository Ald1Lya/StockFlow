<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\stockmovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';

        $products = Product::query()
            ->with(['category', 'brand', 'latestStockMovement'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%")
                      ->orWhereHas('category', function ($categoryQuery) use ($search) {
                          $categoryQuery->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('warehouse.stockmovements.index', [
            'products' => $products,
            'search'   => $search,
        ]);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'product' => 'required|integer|exists:products,id',
        ]);

        $product = Product::with(['category', 'brand', 'latestStockMovement'])
            ->findOrFail($data['product']);

        // Cegah buka form kalau masih ada laporan yang belum diproses Admin
        if ($product->latestStockMovement?->status === 'pending') {
            return redirect()
                ->route('warehouse.stockmovements.index')
                ->with('error', 'Produk ini masih memiliki laporan yang menunggu pengecekan Admin.');
        }

        // Cegah lapor kalau stok sudah 0
        if ($product->stock <= 0) {
            return redirect()
                ->route('warehouse.stockmovements.index')
                ->with('error', 'Stok produk sudah habis, tidak dapat melaporkan pengurangan stok.');
        }

        return view('warehouse.stockmovements.create', [
            'product' => $product,
        ]);
    }

public function store(Request $request)
{
    $data = $request->validate([
        'product_id' => 'required|integer|exists:products,id',
        'quantity'   => 'required|integer|min:1',
        'reason'     => 'required|string|max:255',
    ]);

    $product = Product::with('latestStockMovement')->findOrFail($data['product_id']);
    $latest = $product->latestStockMovement;

    // Kalau laporan terakhir masih pending, akumulasi ke data yang sama (tidak bikin data baru)
    if ($latest && $latest->status === 'pending') {
        $totalQuantity = $latest->quantity + $data['quantity'];

        if ($totalQuantity > $product->stock) {
            return back()
                ->withInput()
                ->withErrors(['quantity' => "Total pengajuan ({$totalQuantity}) melebihi stok tersedia ({$product->stock})."]);
        }

        $latest->update([
            'quantity' => $totalQuantity,
            'reason'   => mb_substr("{$latest->reason} | +{$data['quantity']}: {$data['reason']}", 0, 255),
            'user_id'  => Auth::id(),
        ]);
    } else {
        if ($data['quantity'] > $product->stock) {
            return back()
                ->withInput()
                ->withErrors(['quantity' => "Jumlah melebihi stok tersedia ({$product->stock})."]);
        }

        stockmovement::create([
            'product_id' => $product->id,
            'user_id'    => Auth::id(),
            'type'       => 'out',
            'quantity'   => $data['quantity'],
            'reason'     => $data['reason'],
            'status'     => 'pending',
        ]);
    }

    return redirect()
        ->route('warehouse.stockmovements.index')
        ->with('success', 'Laporan barang rusak berhasil dikirim ke Admin.');
}

    // ADMIN APPROVE (Potong stok sesuai laporan yang disetujui)
    public function approve(Request $request, $id)
    {
        $request->validate([
            'approved_note' => 'required|string|max:255',
        ]);

        $movement = stockmovement::findOrFail($id);

        if ($movement->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses!');
        }

        try {
            DB::transaction(function () use ($movement, $request) {
                $product = Product::lockForUpdate()->findOrFail($movement->product_id);

                if ($product->stock < $movement->quantity) {
                    throw new \RuntimeException('Stok produk saat ini tidak mencukupi untuk dipotong.');
                }

                $product->stock -= $movement->quantity;
                $product->save();

                $movement->update([
                    'status'        => 'approved',
                    'approved_by'   => Auth::id(),
                    'approved_at'   => now(),
                    'approved_note' => $request->approved_note,
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Laporan disetujui. Stok produk resmi dipotong.');
    }

    // ADMIN REJECT (Stok tidak dipotong, tombol lapor otomatis terbuka lagi)
    public function reject(Request $request, $id)
    {
        $request->validate([
            'approved_note' => 'required|string|max:255',
        ]);

        $movement = stockmovement::findOrFail($id);

        if ($movement->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses!');
        }

        $movement->update([
            'status'        => 'rejected',
            'approved_by'   => Auth::id(),
            'approved_at'   => now(),
            'approved_note' => $request->approved_note,
        ]);

        return back()->with('success', 'Laporan ditolak. Stok produk tetap.');
    }
}