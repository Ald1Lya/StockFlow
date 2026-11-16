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

class AcceptpoController extends Controller
{
    // Samakan dengan daftar satuan di ProductController
    private const UNIT_OPTIONS = ['Pcs', 'Kg', 'Sak', 'Lonjor', 'Kotak'];

    public function index(Request $request)
    {
        $search = $request->query('search');
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';

        // Filter satuan — hanya terima nilai yang ada di daftar resmi
        $unit = $request->query('unit');
        $unit = is_string($unit) && in_array($unit, self::UNIT_OPTIONS, true) ? $unit : '';

        $sort = $request->query('sort', 'newest');

        $acceptpos = Purchase::query()
            ->with(['supplier', 'user', 'details.product'])
            ->whereIn('status', ['pending', 'received'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('po_number', 'like', "%{$search}%")
                      ->orWhereHas('supplier', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                });
            })
            // Filter berdasarkan kolom `pcs` di tabel products
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

public function update(Request $request, $id)
    {
        // Pakai findOrFail($id) biar aman apapun nama parameter di routes/web.php ({acceptpo} atau {purchase})
        $acceptpo = Purchase::with('details')->findOrFail($id);

        if ($acceptpo->status !== 'pending') {
            return back()->with('error', 'PO yang sudah diproses tidak dapat diubah statusnya lagi.');
        }

        $data = $request->validate([
            'status' => ['required', Rule::in(['received', 'cancelled'])],
        ]);

        DB::transaction(function () use ($acceptpo, $data) {
            $acceptpo->update(['status' => $data['status']]);

            // Stok & harga modal HANYA berubah kalau dipilih 'received'
            if ($data['status'] === 'received') {
                foreach ($acceptpo->details as $detail) {
                    Product::where('id', $detail->product_id)->update([
                        'stock'      => DB::raw("stock + {$detail->quantity}"),
                        'cost_price' => $detail->unit_cost,
                    ]);

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