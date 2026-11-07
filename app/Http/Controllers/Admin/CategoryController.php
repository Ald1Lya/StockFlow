<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // READ: Menampilkan seluruh kategori fitur search
    public function index(Request $request)
    {
        // Ambil kata pencarian dari query URL.
        $search = $request->query('search');
        // Terima hanya teks, buang spasi tepi, dan batasi panjang pencarian.
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';

        // Siapkan query kategori sekaligus hitung produk terkait.
        $categories = Category::query()
            ->withCount('products')
            // Filter berdasarkan nama atau kode hanya jika ada kata pencarian.
            ->when($search !== '', function (Builder $query) use ($search) {
                // Kelompokkan pencocokan nama dan kode sebagai satu filter.
                $query->where(function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            // Urutkan dari yang terbaru dan pecah hasil menjadi 10 per halaman.
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Render halaman daftar dengan hasil dan kata pencarian aktif.
        return view('admin.categories.index', [
            'categories' => $categories,
            'search' => $search,
        ]);
    }

    // CREATE: Menampilkan form untuk membuat kategori baru.
    public function create()
    {
        // Tampilkan form kosong untuk membuat kategori.
        return view('admin.categories.create');
    }

    // CREATE: Memvalidasi lalu menyimpan kategori baru.
    public function store(Request $request)
    {
        // Validasi nama wajib dan unik; deskripsi boleh kosong.
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
        ]);

        // Ubah nama menjadi kata-kata huruf besar sebagai bahan pembentuk kode.
        $words = explode(' ', strtoupper(trim($data['name'])));
        $code = '';

        // Satu kata: gunakan tiga huruf pertamanya.
        if (count($words) == 1) {
            $code = substr($words[0], 0, 3);
        // Dua kata: dua huruf awal kata pertama dan satu huruf awal kata kedua.
        } elseif (count($words) == 2) {
            $code = substr($words[0], 0, 2).substr($words[1], 0, 1);
        // Tiga kata atau lebih: gunakan huruf awal dari tiga kata pertama.
        } else {
            $code = substr($words[0], 0, 1).substr($words[1], 0, 1).substr($words[2], 0, 1);
        }

        // Simpan bentuk kode awal untuk dipakai saat mencari kode alternatif.
        $originalCode = $code;
        $counter = 1;
        // Tambahkan angka sampai menemukan kode yang belum digunakan.
        while (Category::where('code', $code)->exists()) {
            $code = $originalCode.$counter;
            $counter++;
        }

        // Tambahkan kode hasil pembentukan ke data kategori.
        $data['code'] = $code;

        // Buat slug dari nama dan nomor urut berdasarkan ID kategori terakhir.
        $baseSlug = Str::slug($data['name']);
        $lastCategory = Category::latest('id')->first();
        $number = $lastCategory ? $lastCategory->id + 1 : 1;
        $data['slug'] = $baseSlug.'-'.str_pad($number, 3, '0', STR_PAD_LEFT);

        // Simpan kategori baru ke database.
        Category::create($data);

        // Kembali ke daftar kategori dan tampilkan notifikasi berhasil.
        return redirect()
            ->route('admin.categories.index')
            ->with('success', "Kategori {$data['name']} berhasil ditambahkan dengan kode {$code}.");
    }

    public function destroy(Category $category)
    {
        // Hapus kategori; aturan foreign key database menentukan dampak ke produk terkait.
        $category->delete();

        // Arahkan admin kembali ke daftar kategori dengan pesan hasil.
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori Berhasil Dihapus.');
    }
}
