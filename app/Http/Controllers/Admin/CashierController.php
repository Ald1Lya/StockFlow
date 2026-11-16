<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\sale as Sale;
use App\Models\saledetail as SaleDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller untuk Halaman Utama / Dashboard Kasir
 */
class CashierController extends Controller
{
    /**
     * Menampilkan dashboard kasir: ringkasan omzet, total transaksi shift, dan stok menipis
     */
    public function index(Request $request)
    {
        $search    = trim((string) $request->query('search', ''));
        $startDate = $request->query('start_date');
        $endDate   = $request->query('end_date');
        $cashierId = $request->query('cashier_id');

        // Base query untuk filter yang sama antara tabel & kartu ringkasan
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

        // 1. Hitung Ringkasan Omzet & Jumlah Transaksi sesuai filter aktif
        $totalRevenue      = (clone $baseQuery)->sum('total_amount');
        $totalTransactions = (clone $baseQuery)->count();

        // 2. Hitung Estimasi Laba Kotor (Omzet - Modal Produk terjual)
        $filteredSaleIds = (clone $baseQuery)->pluck('id');
        $totalModal = SaleDetail::query()
            ->whereIn('sale_id', $filteredSaleIds)
            ->join('products', 'saledetails.product_id', '=', 'products.id')
            ->sum(DB::raw('saledetails.quantity * products.cost_price'));

        $grossProfit = $totalRevenue - $totalModal;

        // 3. Ambil daftar transaksi untuk tabel (include rincian item & kasir)
        $sales = $baseQuery
            ->with(['user', 'details.product'])
            ->latest('sale_date')
            ->paginate(15)
            ->withQueryString();

        // Daftar user kasir untuk dropdown filter
        $cashiers = User::where('role', 'cashier')
            ->orderBy('name')
            ->get(['id', 'name']);

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
