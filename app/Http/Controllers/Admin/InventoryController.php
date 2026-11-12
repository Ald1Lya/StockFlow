<?php

namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\stockmovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $products = Product::query()
            ->with(['category', 'brand', 'latestStockMovement.user'])
            ->whereHas('latestStockMovement', function ($query) use ($status) {
                if ($status) {
                    $query->where('status', $status);
                }
            })
            // Urutkan laporan yang masih 'pending' di baris paling atas
            ->orderByDesc(
                stockmovement::selectRaw("CASE WHEN status = 'pending' THEN 1 ELSE 0 END")
                    ->whereColumn('product_id', 'products.id')
                    ->latest('id')
                    ->limit(1)
            )
            // Lalu urutkan berdasarkan laporan terbaru
            ->orderByDesc(
                stockmovement::select('id')
                    ->whereColumn('product_id', 'products.id')
                    ->latest('id')
                    ->limit(1)
            )
            ->paginate(15)
            ->withQueryString();

        return view('admin.report_inventorys.index', [
            'products'      => $products,
            'currentStatus' => $status,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'approved_note' => 'required|string|max:255',
            'action'        => 'required|in:approve,reject',
        ]);

        $movement = stockmovement::findOrFail($id);

        if ($movement->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses!');
        }

        if ($request->action === 'approve') {
            try {
                DB::transaction(function () use ($movement, $request) {
                    $product = Product::lockForUpdate()->findOrFail($movement->product_id);

                    if ($product->stock < $movement->quantity) {
                        throw new \Exception("Stok produk '{$product->name}' tidak cukup!");
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
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }

            return back()->with('success', 'Laporan disetujui. Stok dipotong.');
        }

        $movement->update([
            'status'        => 'rejected',
            'approved_by'   => Auth::id(),
            'approved_at'   => now(),
            'approved_note' => $request->approved_note,
        ]);

        return back()->with('success', 'Laporan ditolak.');
    }
}