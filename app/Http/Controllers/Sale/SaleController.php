<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Models\product as Product;
use App\Models\sale as Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk Halaman Utama / Dashboard Kasir
 */
class SaleController extends Controller
{
    /**
     * Menampilkan dashboard kasir: ringkasan omzet, total transaksi shift, dan stok menipis
     */
    public function index()
    {
        // 1. Tentukan tanggal hari ini (mulai pukul 00:00:00 sampai 23:59:59)
        $today = Carbon::today();

        // 2. Hitung total uang omzet toko yang masuk hari ini dari seluruh transaksi
        $todayRevenue = Sale::whereDate('sale_date', $today)->sum('total_amount');

        // 3. Hitung berapa banyak transaksi yang sudah terjadi di toko hari ini
        $todayTransactions = Sale::whereDate('sale_date', $today)->count();

        // 4. Hitung khusus berapa transaksi yang dilayani oleh kasir yang sedang login saat ini
        $myTransactions = Sale::whereDate('sale_date', $today)
            ->where('user_id', Auth::id())
            ->count();

        // 5. Ambil 5 transaksi penjualan paling baru beserta data kasirnya untuk tabel riwayat cepat
        $recentSales = Sale::with('user')
            ->latest('sale_date')
            ->limit(5)
            ->get();

        // 6. Ambil 5 produk aktif yang stoknya sudah di bawah atau sama dengan batas minimal (min_stock)
        // Berguna agar kasir tahu barang apa yang hampir habis
        $lowStockProducts = Product::where('is_active', true)
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock')
            ->limit(5)
            ->get();

        // 7. Kirim semua data ringkasan ke view resources/views/cashier/dashboard.blade.php
        return view('cashier.dashboard', [
            'todayRevenue'      => $todayRevenue,
            'todayTransactions' => $todayTransactions,
            'myTransactions'    => $myTransactions,
            'recentSales'       => $recentSales,
            'lowStockProducts'  => $lowStockProducts,
        ]);
    }
}
