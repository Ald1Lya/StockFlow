<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\product as Product;
use App\Models\purchase as Purchase;
use App\Models\purchasedetail as PurchaseDetail;
use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AcceptpoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';

    // untuk filter tombol cari data paling baru dan lama pada acceptpo penerimaan barang
        $sort= $request->query('sort', 'newest');

        $acceptpos = Purchase::query()
            ->with(['supplier', 'user'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('po_number', 'like', "%{$search}%")
                      ->orWhereHas('supplier', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                });
            })

            // Menerapkan berdasasrkan parameter
            ->when($sort === 'oldest', function ($query) {
                $query->oldest();
            }, function ($query) {
                $query->latest();
            })


            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('warehouse.acceptpos.index', [
            'acceptpos' => $acceptpos,
            'search'    => $search,
            'sort'      => $sort,
        ]);
    }
    

        public function update(Request $request, Purchase $acceptpo)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'received', 'cancelled'])],
        ]);

        $statusLama = $acceptpo->status;
        $statusBaru = $data['status'];

        DB::transaction(function () use ($acceptpo, $statusLama, $statusBaru) {
            $acceptpo->update(['status' => $statusBaru]);

            // Stok bertambah hanya sekali: saat status pertama kali menjadi received.
            if ($statusLama !== 'received' && $statusBaru === 'received') {
                foreach ($acceptpo->details as $detail) {
                    Product::where('id', $detail->product_id)
                        ->increment('stock', $detail->quantity);
                }
            }

            // Stok dikurangi kembali jika status di-reverse dari received.
            if ($statusLama === 'received' && $statusBaru !== 'received') {
                foreach ($acceptpo->details as $detail) {
                    Product::where('id', $detail->product_id)
                        ->decrement('stock', $detail->quantity);
                }
            }
        });

        return redirect()
            ->route('warehouse.acceptpos.index')
            ->with('success', "Status PO {$acceptpo->po_number} berhasil diperbarui.");
    }
}
