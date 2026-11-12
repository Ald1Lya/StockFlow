<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total produk terdaftar
        $totalProducts = Product::count();

        // Total seluruh kuantitas stok
        $totalStock = Product::sum('stock');

        // Produk dengan stok menipis (stok masih ada)
        $lowStock = Product::whereColumn('stock', '<=', 'min_stock')
            ->where('stock', '>', 0)
            ->count();

        // Produk yang stoknya habis
        $outOfStock = Product::where('stock', '<=', 0)
            ->count();

        // Daftar produk yang perlu segera di-restock
        $lowStockProducts = Product::with(['category', 'brand'])
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock')
            ->take(5)
            ->get();

        return view('warehouse.dashboard', compact(
            'totalProducts',
            'totalStock',
            'lowStock',
            'outOfStock',
            'lowStockProducts'
        ));
    }
}