<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';

        $products = Product::query()
            ->with(['category', 'brand'])
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'search' => $search,
        ]);

    }

    // Menampilkan form tambah untuk product
    public function create(Request $request)
    {
        $categories = Category::query()
            ->orderBy('name')
            ->get();
        $brands = Brand::query()
            ->orderBy('name')
            ->get();

        return view('admin.products.create', [
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => 'nullable|string',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg', // Jika ada upload foto
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Logika Memasukan Gambar ke folder public
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Membuat nama file baru yang unik dengan ekstensi .webp
            $fileName = time().'_'.Str::random(10).'.webp';

            // Menentukan path lokasi penyimpanan asli (storage/app/public/products/)
            $destinationPath = storage_path('app/public/products');

            // Pastikan foldernya sudah ada, jika belum buat otomatis
            if (! file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Mendapatkan tipe file asli untuk menentukan fungsi GD PHP yang dipakai
            $imageType = $file->getClientOriginalExtension();

            // Proses konversi berdasarkan format asal ke resource gambar PHP
            switch (strtolower($imageType)) {
                case 'jpeg':
                case 'jpg':
                    $imageResource = imagecreatefromjpeg($file->getRealPath());
                    break;
                case 'png':
                    $imageResource = imagecreatefrompng($file->getRealPath());
                    // Mempertahankan transparansi jika asalnya PNG transparan
                    imagepalettetotruecolor($imageResource);
                    imagealphablending($imageResource, true);
                    imagesavealpha($imageResource, true);
                    break;
                case 'webp':
                    $imageResource = imagecreatefromwebp($file->getRealPath());
                    break;
                default:
                    return redirect()->back()->withErrors(['image' => 'Format gambar tidak didukung!'])->withInput();
            }

            // Path lengkap beserta nama file webp baru
            $fullPath = $destinationPath.'/'.$fileName;

            // Menyimpan resource gambar tadi menjadi file berformat WebP ke folder tujuan
            // Angka 80 adalah kualitasnya (skala 1-100), 80 adalah standar terbaik untuk web
            imagewebp($imageResource, $fullPath, 80);

            // Hapus resource dari memori RAM server agar tidak berat
            imagedestroy($imageResource);

            // Simpan jalur string path ke array untuk database (sama seperti format laravel bawaan)
            $data['image'] = 'products/'.$fileName;
        }

        // Logika default status aktif
        $data['is_active'] = $request->has('is_active') ? $request->input('is_active') : true;

        // LOGIKA GENERATE SKU (PINDAHAN DARI SUPPLIER)
        // Ambil data kategori berdasarkan category_id yang dipilih user
        $category = Category::findOrFail($data['category_id']);
        // Gunakan kode kategori sebagai prefix (Misal di db kategori baju kodenya "BAJU")
        $prefix = strtoupper($category->code);

        // LOGIKA GENERATE KODE ACAK YANG AMAN (ANTI-KEMBAR)
        do {
            // Membuat 4 karakter alfanumerik acak huruf besar (Contoh: X7R2)
            $randomString = strtoupper(Str::random(4));
            $generatedSku = $prefix.'-'.$randomString; // Hasil: "BAJU-X7R2"

            // Cek ke database tabel PRODUCTS apakah SKU ini sudah pernah dipakai
            $skuExists = Product::where('sku', $generatedSku)->exists();
        } while ($skuExists); // Jika sudah ada, loop ulang sampai dapat yang unik

        // Masukkan SKU yang sudah pasti unik ke dalam array data sebelum disave
        $data['sku'] = $generatedSku;

        // 3. Simpan ke Database
        Product::create($data);

        // 4. Redirect ke halaman index produk Anda
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan dengan SKU: '.$generatedSku);

    }
}
