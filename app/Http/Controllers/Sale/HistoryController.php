<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Models\sale as Sale;
use Illuminate\Http\Request;

/**
 * Controller untuk Riwayat Belanja Kasir & Cetak Ulang Nota
 */
class HistoryController extends Controller
{
    /**
     * Menampilkan daftar seluruh riwayat transaksi penjualan (dengan filter & pagination)
     */
    public function index(Request $request)
    {
        // 1. Ambil keyword pencarian nomor invoice dan filter tanggal jika diisi user
        $search = trim((string) $request->query('search', ''));
        $date   = $request->query('date');

        // 2. Query data transaksi beserta relasi nama kasirnya
        $sales = Sale::with('user')
            // Filter nomor invoice jika ada pencarian
            ->when($search !== '', fn ($q) => $q->where('invoice_number', 'like', "%{$search}%"))
            // Filter tanggal transaksi jika tanggal dipilih
            ->when($date, fn ($q) => $q->whereDate('sale_date', $date))
            // Urutkan dari transaksi paling baru
            ->latest('sale_date')
            // Buat pagination per 10 data dan pertahankan parameter URL search & date
            ->paginate(10)
            ->withQueryString();

        // 3. Kirim data ke view tabel riwayat
        return view('cashier.histories.index', [
            'sales'  => $sales,
            'search' => $search,
            'date'   => $date,
        ]);
    }

    /**
     * Menampilkan detail satu nota/struk spesifik untuk dilihat atau dicetak ulang
     */
    public function show($id)
    {
        // Ambil transaksi beserta data kasir dan daftar barang belanjaannya (details -> product)
        $sale = Sale::with(['user', 'details.product'])->findOrFail($id);

        // Tampilkan halaman struk nota transaksi
        return view('cashier.histories.show', [
            'sale' => $sale,
        ]);
    }
}
