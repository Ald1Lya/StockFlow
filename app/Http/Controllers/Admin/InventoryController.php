<?php

namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\stockmovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controller Laporan Inventaris / Approval Mutasi Barang Rusak (Sisi Admin)
 * Digunakan oleh Admin untuk menyetujui (approve) atau menolak (reject) laporan barang rusak/hilang dari gudang.
 */
class InventoryController extends Controller
{
    /**
     * Menampilkan daftar laporan mutasi stok dari gudang (status: pending, approved, rejected)
     */
    public function index(Request $request)
    {
        // 1. Ambil filter status jika dipilih oleh admin (pending/approved/rejected)
        $status = $request->query('status');

        // 2. Query produk yang memiliki riwayat mutasi stok
        $products = Product::query()
            ->with(['category', 'brand', 'latestStockMovement.user'])
            ->whereHas('latestStockMovement', function ($query) use ($status) {
                if ($status) {
                    $query->where('status', $status);
                }
            })
            // Prioritaskan baris laporan yang masih 'pending' di posisi paling atas agar segera ditindaklanjuti
            ->orderByDesc(
                stockmovement::selectRaw("CASE WHEN status = 'pending' THEN 1 ELSE 0 END")
                    ->whereColumn('product_id', 'products.id')
                    ->latest('id')
                    ->limit(1)
            )
            // Kemudian urutkan berdasarkan waktu laporan terbaru
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

    /**
     * Memproses keputusan Admin: Menyetujui (potong stok) atau Menolak laporan
     */
    public function update(Request $request, $id)
    {
        // 1. Validasi catatan admin dan aksi yang dipilih (approve / reject)
        $request->validate([
            'approved_note' => 'required|string|max:255',
            'action'        => 'required|in:approve,reject',
        ]);

        $movement = stockmovement::findOrFail($id);

        // 2. Pastikan laporan masih pending
        if ($movement->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses!');
        }

        // 3. Aksi Approve: Potong stok produk di tabel products
        if ($request->action === 'approve') {
            try {
                DB::transaction(function () use ($movement, $request) {
                    $product = Product::lockForUpdate()->findOrFail($movement->product_id);

                    // Validasi sisa stok mencukupi untuk dipotong
                    if ($product->stock < $movement->quantity) {
                        throw new \Exception("Stok produk '{$product->name}' tidak cukup!");
                    }

                    // Potong kuantitas stok riil
                    $product->stock -= $movement->quantity;
                    $product->save();

                    // Perbarui status mutasi menjadi approved
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

        // 4. Aksi Reject: Status ditolak dan stok produk tetap utuh
        $movement->update([
            'status'        => 'rejected',
            'approved_by'   => Auth::id(),
            'approved_at'   => now(),
            'approved_note' => $request->approved_note,
        ]);

        return back()->with('success', 'Laporan ditolak.');
    }
}