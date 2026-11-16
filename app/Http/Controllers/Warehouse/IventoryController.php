<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\product as Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class IventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';

        $stockFilter = $request->query('stock_status', '');

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
            ->when($stockFilter === 'low', function (Builder $query) {
                $query->where('stock', '>', 0)
                      ->whereColumn('stock', '<=', 'min_stock');
            })
            ->when($stockFilter === 'out', function (Builder $query) {
                $query->where('stock', '<=', 0);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('warehouse.iventorys.index', [
            'iventorys'   => $iventorys,
            'search'      => $search,
            'stockFilter' => $stockFilter,
        ]);
    }
}