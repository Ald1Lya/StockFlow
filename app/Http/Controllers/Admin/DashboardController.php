<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\product as Product;
use App\Models\purchase as Purchase;
use App\Models\sale as Sale;
use App\Models\saledetail as SaleDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Controller Dashboard Admin
 * Menghitung ringkasan performa toko, statistik keuangan, grafik, serta status stok.
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama admin dengan ringkasan metrik & grafik
     */
    public function index()
    {
        // Tentukan acuan waktu sekarang, awal bulan ini, dan rentang bulan lalu
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // 1. KARTU PENDAPATAN (Bulan Ini vs Bulan Lalu)
        // Hitung total omzet bulan berjalan dan bulan lalu, lalu hitung persentase pertumbuhannya
        $currentRevenue = Sale::where('sale_date', '>=', $startOfMonth)->sum('total_amount');
        $lastMonthRevenue = Sale::whereBetween('sale_date', [$startOfLastMonth, $endOfLastMonth])->sum('total_amount');
        $revenueGrowth = $lastMonthRevenue > 0
            ? round((($currentRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : ($currentRevenue > 0 ? 100 : 0);

        // 2. KARTU TRANSAKSI / PESANAN & PO PENDING
        // Menghitung jumlah pesanan bulan ini vs bulan lalu, serta PO supplier yang masih pending
        $currentOrders = Sale::where('sale_date', '>=', $startOfMonth)->count();
        $lastMonthOrders = Sale::whereBetween('sale_date', [$startOfLastMonth, $endOfLastMonth])->count();
        $orderGrowth = $lastMonthOrders > 0
            ? round((($currentOrders - $lastMonthOrders) / $lastMonthOrders) * 100, 1)
            : ($currentOrders > 0 ? 100 : 0);
        $pendingPurchases = Purchase::where('status', 'pending')->count();

        // 3. KARTU PRODUK AKTIF & KATEGORI
        $activeProductsCount = Product::where('is_active', true)->count();
        $categoriesCount = Category::count();

        // 4. KARTU STOK RENDAH
        // Ambil produk yang stoknya sudah mencapai atau di bawah batas minimum (min_stock)
        $lowStockProducts = Product::where('is_active', true)
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock')
            ->get();
        $lowStockCount = $lowStockProducts->count();

        // 5. DATA GRAFIK SVG (7 Bulan Terakhir & 7 Hari Terakhir)
        // Menghasilkan koordinat garis & area SVG untuk grafik interaktif tanpa library berat
        $monthlyChart = $this->buildMonthlyChartData($now);
        $weeklyChart  = $this->buildWeeklyChartData($now);

        // 6. TOP 5 PRODUK TERLARIS
        // Agregasi jumlah kuantitas penjualan tertinggi dari tabel saledetails
        $topProducts = SaleDetail::query()
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->with('product:id,name,sku,pcs')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // 7. 5 TRANSAKSI TERBARU BESERTA KASIRNYA
        $recentSales = Sale::with('user:id,name')
            ->latest('sale_date')
            ->limit(5)
            ->get();

        // 8. Kirim semua metrik dan dataset ke view dashboard admin
        return view('admin.dashboard', [
            'currentRevenue'      => $currentRevenue,
            'lastMonthRevenue'    => $lastMonthRevenue,
            'revenueGrowth'       => $revenueGrowth,
            'currentOrders'       => $currentOrders,
            'orderGrowth'         => $orderGrowth,
            'pendingPurchases'    => $pendingPurchases,
            'activeProductsCount' => $activeProductsCount,
            'categoriesCount'     => $categoriesCount,
            'lowStockCount'       => $lowStockCount,
            'lowStockList'        => $lowStockProducts->take(5),
            'monthlyChart'        => $monthlyChart,
            'weeklyChart'         => $weeklyChart,
            'topProducts'         => $topProducts,
            'recentSales'         => $recentSales,
        ]);
    }

    /**
     * Membentuk dataset pendapatan 7 bulan terakhir untuk grafik bulanan
     */
    private function buildMonthlyChartData(Carbon $now): array
    {
        $labels = [];
        $values = [];

        for ($i = 6; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $labels[] = $month->translatedFormat('M');
            $sum = Sale::whereYear('sale_date', $month->year)
                ->whereMonth('sale_date', $month->month)
                ->sum('total_amount');
            // Konversi ke juta rupiah (1 desimal) agar pas dengan skala grafik
            $values[] = round($sum / 1000000, 2);
        }

        return $this->mapToSvgCoordinates($labels, $values, '7 bulan terakhir · dalam juta rupiah');
    }

    /**
     * Membentuk dataset pendapatan 7 hari terakhir untuk grafik mingguan
     */
    private function buildWeeklyChartData(Carbon $now): array
    {
        $labels = [];
        $values = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i);
            $labels[] = $day->format('d/m');
            $sum = Sale::whereDate('sale_date', $day->toDateString())->sum('total_amount');
            // Konversi ke ribu rupiah
            $values[] = round($sum / 1000, 1);
        }

        return $this->mapToSvgCoordinates($labels, $values, '7 hari terakhir · dalam ribu rupiah');
    }

    /**
     * Menghitung posisi koordinat pixel X & Y agar data angka dipetakan presisi ke tag <svg>
     */
    private function mapToSvgCoordinates(array $labels, array $values, string $subtitle): array
    {
        $xCoords = [40, 127, 213, 300, 387, 473, 560];
        $maxVal = max($values) > 0 ? max($values) : 100;
        // Bulatkan batas atas skala Y biar rapi
        $ceiling = ceil($maxVal / 10) * 10;
        if ($ceiling <= 0) $ceiling = 100;

        $points = [];
        foreach ($values as $idx => $val) {
            $x = $xCoords[$idx];
            // Area grafik SVG Y dari 30 (atas/max) sampai 180 (bawah/0)
            $y = round(180 - (($val / $ceiling) * 150), 1);
            $points[] = ['x' => $x, 'y' => $y, 'val' => $val, 'label' => $labels[$idx]];
        }

        $polyline = collect($points)->map(fn ($p) => "{$p['x']},{$p['y']}")->implode(' ');
        $areaPath = 'M' . collect($points)->map(fn ($p) => "{$p['x']},{$p['y']}")->implode(' L') . ' L560,180 L40,180 Z';

        $yTicks = [
            ['y' => 34,  'label' => $ceiling],
            ['y' => 74,  'label' => round($ceiling * 0.75)],
            ['y' => 114, 'label' => round($ceiling * 0.5)],
            ['y' => 154, 'label' => round($ceiling * 0.25)],
            ['y' => 184, 'label' => 0],
        ];

        return [
            'subtitle' => $subtitle,
            'points'   => $points,
            'polyline' => $polyline,
            'areaPath' => $areaPath,
            'yTicks'   => $yTicks,
            'lastPoint'=> end($points),
        ];
    }
}