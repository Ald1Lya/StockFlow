<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Ambil pencarian dari URL; admin dapat mencari nama produk atau SKU.
        $search = $request->query('search');
        // Rapikan pencarian dan batasi panjangnya supaya input tetap terkendali.
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';

        // Ambil produk beserta relasi kategori/brand untuk ditampilkan di daftar.
        $products = Product::query()
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
        return view('admin.products.index', [
            'products' => $products,
            'search' => $search,
        ]);

    }

    // Menampilkan form tambah untuk product
    public function create(Request $request): View|JsonResponse
    {
        // JavaScript meminta JSON untuk mencari kategori/brand lewat route resource create yang sama.
        if ($request->expectsJson()) {
            // Batasi jenis data yang dicari dan panjang teks pencarian.
            $validated = $request->validate([
                'type' => ['required', Rule::in(['category', 'brand'])],
                'search' => ['nullable', 'string', 'max:100'],
            ]);
            // Rapikan teks sebelum dipakai sebagai filter database.
            $search = trim($validated['search'] ?? '');

            // Tentukan model yang dicari sesuai jenis pilihan dari form.
            $options = match ($validated['type']) {
                'category' => Category::query(),
                'brand' => Brand::query(),
            };

            // Cari nama yang cocok, urutkan, lalu ambil 11 hasil untuk tahu apakah masih ada lanjutan.
            $options = $options
                ->when($search !== '', function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->orderBy('name')
                ->limit(11)
                ->get(['id', 'name']);

            // Kirim maksimal 10 pilihan ke browser dan beri tahu jika hasil lebih banyak.
            return response()->json([
                'data' => $options->take(10)->values(),
                'has_more' => $options->count() > 10,
            ]);
        }

        // Ambil lagi ID pilihan lama jika submit sebelumnya gagal validasi.
        $categoryId = $request->old('category_id');
        $brandId = $request->old('brand_id');

        // Cari record kategori/brand terpilih agar nama bisa ditampilkan kembali pada form.
        return view('admin.products.create', [
            'selectedCategory' => is_scalar($categoryId) && $categoryId !== ''
                ? Category::query()->find($categoryId)
                : null,
            'selectedBrand' => is_scalar($brandId) && $brandId !== ''
                ? Brand::query()->find($brandId)
                : null,
        ]);
    }

    public function store(Request $request)
    {
        // Validasi seluruh nilai form sebelum melakukan pemrosesan atau penyimpanan.
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => 'nullable|string',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Logika Memasukan Gambar ke folder public
        if ($request->hasFile('image')) {
            // Ambil file gambar yang dikirim dari form.
            $file = $request->file('image');

            // Membuat nama file baru yang unik dengan ekstensi .webp
            $fileName = time().'_'.Str::random(10).'.webp';

            // Menentukan path lokasi penyimpanan asli (storage/app/public/products/)
            $destinationPath = storage_path('app/public/products');

            // Pastikan foldernya sudah ada, jika belum buat otomatis
            if (! file_exists($destinationPath)) {
                // Buat folder tujuan beserta folder induknya dengan izin akses yang ditentukan.
                mkdir($destinationPath, 0755, true);
            }

            // Ambil ekstensi file untuk memilih fungsi pembaca gambar GD yang sesuai.
            $imageType = $file->getClientOriginalExtension();

            // Validasi di atas saat ini menerima JPEG/JPG/PNG; cabang WebP tersedia jika aturan validasi mengizinkannya.
            switch (strtolower($imageType)) {
                case 'jpeg':
                case 'jpg':
                    // Baca JPEG/JPG sebagai resource gambar GD.
                    $imageResource = imagecreatefromjpeg($file->getRealPath());
                    break;
                case 'png':
                    // Baca PNG dan atur mode warna/transparansi agar alpha tetap terjaga.
                    $imageResource = imagecreatefrompng($file->getRealPath());
                    // Mempertahankan transparansi jika asalnya PNG transparan
                    imagepalettetotruecolor($imageResource);
                    imagealphablending($imageResource, true);
                    imagesavealpha($imageResource, true);
                    break;
                case 'webp':
                    // Baca WebP sebagai resource gambar GD.
                    $imageResource = imagecreatefromwebp($file->getRealPath());
                    break;
                default:
                    // Hentikan proses dengan pesan error jika format tidak didukung.
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
        // Jika form tidak mengirim status, tetapkan produk sebagai aktif.
        $data['is_active'] = $request->has('is_active') ? $request->input('is_active') : true;

        // LOGIKA GENERATE SKU
        $category = Category::findOrFail($data['category_id']);
        $prefixKategori = strtoupper($category->code);

        $prefixBrand = '';
        if (!empty($data['brand_id'])) {
            $brand = Brand::findOrFail($data['brand_id']);
            $prefixBrand = strtoupper($brand->code);
        }

        do {
            $randomStr    = strtoupper(Str::random(4));
            $generatedSku = $prefixBrand !== ''
                ? $prefixKategori . '-' . $prefixBrand . '-' . $randomStr
                : $prefixKategori . '-' . $randomStr;

            $skuExists = Product::where('sku', $generatedSku)->exists();
        } while ($skuExists);

        // Masukkan SKU yang sudah pasti unik ke dalam array data sebelum disave
        $data['sku'] = $generatedSku;
                    
        // 3. Simpan ke Database
        // Buat record produk; atributnya dibatasi oleh fillable pada model Product.
        Product::create($data);

        // 4. Redirect ke halaman index produk Anda
        // Kembali ke daftar produk dan tampilkan SKU yang baru dibuat.
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan dengan SKU: '.$generatedSku);

    }



        public function edit(Product $product)
    {
        // Gunakan product dari route model binding untuk mengisi form edit.
        return view('admin.products.edit', [
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        // 1. Validasi data dari form
        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'brand_id'     => 'nullable|exists:brands,id',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cost_price'   => 'required|numeric|min:0',
            'selling_price'=> 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'min_stock'    => 'required|integer|min:0',
        ]);

        // 2. Atur status aktif — checkbox tidak mengirim value jika tidak dicentang,
        //    sehingga tidak boleh masuk validasi; tangkap manual lalu paksa ke boolean.
        $data['is_active'] = $request->boolean('is_active');

        // 3. Cek apakah kategori atau brand berubah
        $categoryChanged = $product->category_id != $data['category_id'];
        $brandChanged = $product->brand_id != $data['brand_id'];

        if ($categoryChanged || $brandChanged) {

            $category = Category::findOrFail($data['category_id']);

            $prefixKategori = strtoupper($category->code);

            // Brand boleh kosong
            $prefixBrand = '';

            if (!empty($data['brand_id'])) {
                $brand = Brand::findOrFail($data['brand_id']);
                $prefixBrand = strtoupper($brand->code);
            }

            // Generate SKU baru
            do {
                $randomStr = strtoupper(Str::random(4));

                $generatedSku = $prefixBrand !== ''
                    ? $prefixKategori.'-'.$prefixBrand.'-'.$randomStr
                    : $prefixKategori.'-'.$randomStr;

                $skuExists = Product::where('sku', $generatedSku)
                    ->where('id', '!=', $product->id)
                    ->exists();

            } while ($skuExists);

            $data['sku'] = $generatedSku;
        }

        // 4. Proses gambar jika ada upload baru
        if ($request->hasFile('image')) {

                $file = $request->file('image');

                $fileName = time().'_'.Str::random(10).'.webp';

                $destinationPath = storage_path('app/public/products');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
            }

                $imageType = strtolower($file->getClientOriginalExtension());

                switch ($imageType) {
                    case 'jpeg':
                    case 'jpg':
                        $imageResource = imagecreatefromjpeg($file->getRealPath());
                        break;

                    case 'png':
                        $imageResource = imagecreatefrompng($file->getRealPath());

                        imagepalettetotruecolor($imageResource);
                        imagealphablending($imageResource, true);
                        imagesavealpha($imageResource, true);
                        break;

                    case 'webp':
                        $imageResource = imagecreatefromwebp($file->getRealPath());
                        break;

                    default:
                        return back()
                            ->withErrors(['image' => 'Format gambar tidak didukung!'])
                            ->withInput();
            }

            $fullPath = $destinationPath.'/'.$fileName;

            imagewebp($imageResource, $fullPath, 80);

            imagedestroy($imageResource);

            // Hapus gambar lama
            if ($product->image) {
                $oldImage = storage_path('app/public/'.$product->image);
                if (file_exists($oldImage)) {
                        unlink($oldImage);
                    }
                }

            // Simpan path gambar baru
               $data['image'] = 'products/'.$fileName;
            }
                // 5. Update data produk
                   $product->update($data);
                    // 6. Redirect
                    return redirect()
                    ->route('admin.products.index')
                    ->with('success', 'Produk berhasil diperbarui.');
        }


     public function destroy(Product $product)
    {
        // Hapus kategori; aturan foreign key database menentukan dampak ke produk terkait.
        $product->delete();

        // Arahkan admin kembali ke daftar kategori dengan pesan hasil.
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Kategori Berhasil Dihapus.');
    }

}
