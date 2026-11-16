<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * Controller Dashboard Gudang
 * Menghitung ringkasan stok fisik produk, produk kritis/menipis, dan produk yang habis.
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard operasional staf gudang
     */
    public function index()
    {
        // 1. Total jenis produk yang terdaftar aktif
        $totalProducts = Product::count();

        // 2. Akumulasi total kuantitas stok semua barang di gudang
        $totalStock = Product::sum('stock');

        // 3. Menghitung produk yang stoknya menipis (masih ada sisa tapi <= min_stock)
        $lowStock = Product::whereColumn('stock', '<=', 'min_stock')
            ->where('stock', '>', 0)
            ->count();

        // 4. Menghitung produk yang stok fisiknya sudah 0 atau habis total
        $outOfStock = Product::where('stock', '<=', 0)
            ->count();

        // 5. Mengambil 5 produk prioritas yang paling perlu segera dilakukan re-stock (PO)
        $lowStockProducts = Product::with(['category', 'brand'])
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock')
            ->take(5)
            ->get();

        // 6. Tampilkan ke view dashboard gudang
        return view('warehouse.dashboard', compact(
            'totalProducts',
            'totalStock',
            'lowStock',
            'outOfStock',
            'lowStockProducts'
        ));
    }
}