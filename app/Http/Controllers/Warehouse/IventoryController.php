<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IventoryController extends Controller
{
    public function index(Request $request)
    {
        // Ambil pencarian dari URL; admin dapat mencari nama produk atau SKU.
        $search = $request->query('search');
        // Rapikan pencarian dan batasi panjangnya supaya input tetap terkendali.
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';

        // PERBAIKAN: Menggunakan Product::query() sesuai model yang di-import
        $Iventorys = Product::query()
            ->with(['category', 'brand'])
            // Tambahkan filter nama atau SKU jika pencarian tidak kosong.
            ->when($search !== '', function (Builder $query) use ($search) {
                // Kelompokkan dua kondisi pencarian agar salah satunya boleh cocok.
                $query->where(function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            // Urutkan produk terbaru, tampilkan 10 per halaman, dan pertahankan pencarian.
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Kirim hasil query dan nilai pencarian ke template daftar produk.
        return view('warehouse.iventorys.index', [
            'iventorys' => $Iventorys,
            'search' => $search,
        ]);
    }
}
