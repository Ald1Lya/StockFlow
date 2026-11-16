<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Controller Penerimaan Barang PO (Sisi Staf Gudang)
 * Bertugas memproses Purchase Order dari Admin: mengubah status menjadi 'received' (stok masuk) atau 'cancelled'.
 */
class AcceptpoController extends Controller
{
    // Samakan dengan daftar satuan di ProductController
    private const UNIT_OPTIONS = ['Pcs', 'Kg', 'Sak', 'Lonjor', 'Kotak'];

    /**
     * Menampilkan daftar PO yang masuk ke gudang (status: pending atau received)
     */
    public function index(Request $request)
    {
        // 1. Ambil keyword pencarian no PO / supplier
        $search = $request->query('search');
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';

        // 2. Filter satuan barang — hanya terima nilai yang ada di daftar resmi
        $unit = $request->query('unit');
        $unit = is_string($unit) && in_array($unit, self::UNIT_OPTIONS, true) ? $unit : '';

        // 3. Pilihan pengurutan tanggal (terbaru vs terlama)
        $sort = $request->query('sort', 'newest');

        // 4. Query data PO yang statusnya masih pending (perlu diproses) atau sudah received
        $acceptpos = Purchase::query()
            ->with(['supplier', 'user', 'details.product'])
            ->whereIn('status', ['pending', 'received'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('po_number', 'like', "%{$search}%")
                      ->orWhereHas('supplier', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                });
            })
            // Filter berdasarkan satuan produk di tabel products
            ->when($unit !== '', function ($query) use ($unit) {
                $query->whereHas('details.product', fn ($q) => $q->where('pcs', $unit));
            })
            ->when($sort === 'oldest', fn ($q) => $q->oldest(), fn ($q) => $q->latest())
            ->paginate(10)
            ->withQueryString();

        return view('warehouse.acceptpos.index', [
            'acceptpos'    => $acceptpos,
            'search'       => $search,
            'unit'         => $unit,
            'unitOptions'  => self::UNIT_OPTIONS,
            'sort'         => $sort,
        ]);
    }

    /**
     * Memproses penerimaan barang PO (Ubah status ke 'received' atau 'cancelled')
     * Saat status menjadi 'received', stok otomatis bertambah dan riwayat mutasi 'in' dicatat.
     */
    public function update(Request $request, $id)
    {
        // 1. Ambil data PO beserta rincian item barangnya
        $acceptpo = Purchase::with('details')->findOrFail($id);

        // 2. Cegah perubahan jika PO sudah pernah diproses sebelumnya
        if ($acceptpo->status !== 'pending') {
            return back()->with('error', 'PO yang sudah diproses tidak dapat diubah statusnya lagi.');
        }

        // 3. Validasi status tujuan (hanya boleh diterima atau dibatalkan)
        $data = $request->validate([
            'status' => ['required', Rule::in(['received', 'cancelled'])],
        ]);

        // 4. Jalankan Database Transaction untuk menjaga integritas data stok
        DB::transaction(function () use ($acceptpo, $data) {
            // Update status PO
            $acceptpo->update(['status' => $data['status']]);

            // Jika status diterima (received), tambahkan stok dan catat mutasi masuk
            if ($data['status'] === 'received') {
                foreach ($acceptpo->details as $detail) {
                    // Tambah stok fisik produk dan update harga modal terakhir
                    Product::where('id', $detail->product_id)->update([
                        'stock'      => DB::raw("stock + {$detail->quantity}"),
                        'cost_price' => $detail->unit_cost,
                    ]);

                    // Catat ke kartu stok / log mutasi (tipe: in, reference: purchase)
                    StockMovement::create([
                        'product_id'     => $detail->product_id,
                        'user_id'        => Auth::id(),
                        'type'           => 'in',
                        'quantity'       => $detail->quantity,
                        'reference_type' => 'purchase',
                        'reference_id'   => $acceptpo->id,
                        'reason'         => "Penerimaan barang dari PO {$acceptpo->po_number}",
                        'status'         => 'approved',
                        'approved_by'    => Auth::id(),
                        'approved_at'    => now(),
                    ]);
                }
            }
        });

        return redirect()
            ->route('warehouse.acceptpos.index')
            ->with('success', "Status PO {$acceptpo->po_number} berhasil diperbarui.");
    }
}