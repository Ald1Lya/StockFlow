<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\product as Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Controller Inventaris Gudang (Katalog Stok Gudang)
 * Digunakan oleh staf gudang untuk mengecek ketersediaan fisik stok produk secara real-time.
 */
class IventoryController extends Controller
{
    /**
     * Menampilkan daftar inventaris produk dengan filter pencarian dan status stok (menipis/habis)
     */
    public function index(Request $request)
    {
        // 1. Ambil keyword pencarian nama produk, SKU, kategori, atau brand
        $search = $request->query('search');
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';

        // 2. Ambil filter status stok: 'low' (menipis) atau 'out' (habis)
        $stockFilter = $request->query('stock_status', '');

        // 3. Query produk beserta kategori dan mereknya
        $iventorys = Product::query()
            ->with(['category', 'brand'])
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhereHas('category', fn ($cat) => $cat->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('brand', fn ($br) => $br->where('name', 'like', "%{$search}%"));
                });
            })
            // Filter stok menipis: stok > 0 dan stok <= min_stock
            ->when($stockFilter === 'low', function (Builder $query) {
                $query->where('stock', '>', 0)
                      ->whereColumn('stock', '<=', 'min_stock');
            })
            // Filter stok habis: stok <= 0
            ->when($stockFilter === 'out', function (Builder $query) {
                $query->where('stock', '<=', 0);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        // 4. Render view tabel inventaris gudang
        return view('warehouse.iventorys.index', [
            'iventorys'   => $iventorys,
            'search'      => $search,
            'stockFilter' => $stockFilter,
        ]);
    }
}