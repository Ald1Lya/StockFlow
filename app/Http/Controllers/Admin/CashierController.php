<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\sale as Sale;
use App\Models\saledetail as SaleDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller Laporan Penjualan Kasir (Sisi Admin)
 * Digunakan oleh Admin untuk memantau omzet, laba kotor, dan riwayat transaksi semua kasir.
 */
class CashierController extends Controller
{
    /**
     * Menampilkan rekap laporan penjualan dengan filter tanggal, kasir, dan kata kunci
     */
    public function index(Request $request)
    {
        // 1. Ambil parameter filter dari request URL (pencarian, rentang tanggal, & filter per kasir)
        $search    = trim((string) $request->query('search', ''));
        $startDate = $request->query('start_date');
        $endDate   = $request->query('end_date');
        $cashierId = $request->query('cashier_id');

        // 2. Susun base query agar perhitungan statistik dan tabel data menggunakan kriteria filter yang sama
        $baseQuery = Sale::query()
            ->when($search !== '', function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%");
            })
            ->when($startDate, function ($q) use ($startDate) {
                $q->whereDate('sale_date', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                $q->whereDate('sale_date', '<=', $endDate);
            })
            ->when($cashierId, function ($q) use ($cashierId) {
                $q->where('user_id', $cashierId);
            });

        // 3. Hitung ringkasan total omzet dan total frekuensi transaksi sesuai filter aktif
        $totalRevenue      = (clone $baseQuery)->sum('total_amount');
        $totalTransactions = (clone $baseQuery)->count();

        // 4. Hitung estimasi Laba Kotor: (Total Omzet - Total Harga Modal Produk yang terjual)
        $filteredSaleIds = (clone $baseQuery)->pluck('id');
        $totalModal = SaleDetail::query()
            ->whereIn('sale_id', $filteredSaleIds)
            ->join('products', 'saledetails.product_id', '=', 'products.id')
            ->sum(DB::raw('saledetails.quantity * products.cost_price'));

        $grossProfit = $totalRevenue - $totalModal;

        // 5. Ambil data penjualan dengan pagination (15 per halaman) beserta data kasir dan rincian produknya
        $sales = $baseQuery
            ->with(['user', 'details.product'])
            ->latest('sale_date')
            ->paginate(15)
            ->withQueryString();

        // 6. Ambil daftar user dengan role cashier untuk opsi dropdown filter di halaman view
        $cashiers = User::where('role', 'cashier')
            ->orderBy('name')
            ->get(['id', 'name']);

        // 7. Render view laporan penjualan
        return view('admin.report_sales.index', [
            'sales'             => $sales,
            'cashiers'          => $cashiers,
            'totalRevenue'      => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'grossProfit'       => $grossProfit,
            'search'            => $search,
            'startDate'         => $startDate,
            'endDate'           => $endDate,
            'cashierId'         => $cashierId,
        ]);
    }
}
