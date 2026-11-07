<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(Request $request): View
    {
        // Ambil teks pencarian dari URL, misalnya ?search=semen.
        $search = $request->query('search');
        // Pastikan pencarian berupa teks, rapikan spasi, dan batasi panjangnya.
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';

        // Mulai query brand dan hitung jumlah produk tanpa mengambil semua produknya.
        $brands = Brand::query()
            ->withCount('products')
            // Tambahkan filter nama/kode hanya jika admin mengisi pencarian.
            ->when($search !== '', function (Builder $query) use ($search) {
                // Kelompokkan kondisi agar nama atau kode yang cocok sama-sama ditemukan.
                $query->where(function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            // Urutkan brand terbaru, tampilkan 10 per halaman, dan pertahankan filter di tautan halaman.
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Kirim data brand dan kata pencarian ke template daftar brand.
        return view('admin.brands.index', [
            'brands' => $brands,
            'search' => $search,
        ]);
    }

    
    public function create(): View
    {
        // Tampilkan form kosong; form brand tidak memerlukan data kategori.
        return view('admin.brands.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Periksa input form dan pastikan nama brand belum dipakai.
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:brands,name'],
            'description' => ['nullable', 'string'],
        ]);

        // Pecah nama menjadi kata-kata huruf besar untuk membentuk kode brand.
        $words = explode(' ', strtoupper(trim($data['name'])));
        $code = '';

        // Untuk satu kata, ambil tiga huruf pertama.
        if (count($words) == 1) {
            $code = substr($words[0], 0, 3);
        // Untuk dua kata, ambil dua huruf dari kata pertama dan satu dari kata kedua.
        } elseif (count($words) == 2) {
            $code = substr($words[0], 0, 2) . substr($words[1], 0, 1);
        // Untuk tiga kata atau lebih, ambil huruf pertama dari tiga kata pertama.
        } else {
            $code = substr($words[0], 0, 1) . substr($words[1], 0, 1) . substr($words[2], 0, 1);
        }

        // Simpan kode awal agar angka pembeda selalu ditambahkan ke kode dasar.
        $originalCode = $code;
        $counter = 1;
        // Jika kode sudah dipakai, coba lagi dengan akhiran angka.
        while (Brand::where('code', $code)->exists()) {
            $code = $originalCode . $counter; 
            $counter++;
        }

        // Masukkan kode yang sudah unik ke data yang akan disimpan.
        $data['code'] = $code;

        // Buat slug URL dari nama brand dan nomor berurutan berdasarkan ID terakhir.
        $baseSlug = Str::slug($data['name']);
        $lastBrand = Brand::latest('id')->first();
        $number = $lastBrand ? $lastBrand->id + 1 : 1;
        $data['slug'] = $baseSlug . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);

        // Buat baris brand baru menggunakan data yang sudah divalidasi dan dilengkapi.
        Brand::create($data);

        // Kembali ke daftar brand dengan pesan bahwa penyimpanan berhasil.
        return redirect()
            ->route('admin.brands.index')
            ->with('success', "Brand {$data['name']} berhasil ditambahkan dengan kode {$code}.");
    }

    public function edit(Brand $brand): View
    {
        // Route model binding mengisi $brand; kirim brand tersebut ke form edit.
        return view('admin.brands.edit', [
            'brand' => $brand,
        ]);
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        // Validasi nama dan abaikan nama brand ini sendiri saat memeriksa duplikasi.
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:brands,name,'.$brand->id],
            'description' => ['nullable', 'string'],
        ]);

        // Kode dan slug hanya dibuat ulang jika admin mengganti nama brand.
        if ($brand->name !== $data['name']) {
            // Bentuk kode dari nama baru dengan aturan huruf yang sama seperti saat membuat brand.
            $words = explode(' ', strtoupper(trim($data['name'])));
            $code = '';

            // Satu kata: tiga huruf pertama.
            if (count($words) == 1) {
                $code = substr($words[0], 0, 3);
            // Dua kata: dua huruf pertama dari kata pertama dan satu dari kata kedua.
            } elseif (count($words) == 2) {
                $code = substr($words[0], 0, 2) . substr($words[1], 0, 1);
            // Tiga kata atau lebih: huruf awal tiga kata pertama.
            } else {
                $code = substr($words[0], 0, 1) . substr($words[1], 0, 1) . substr($words[2], 0, 1);
            }

            // Siapkan kode dasar dan angka pembeda jika kode dipakai brand lain.
            $originalCode = $code;
            $counter = 1;
            // Abaikan brand yang sedang diedit saat memeriksa bentrok kode.
            while (Brand::where('code', $code)->where('id', '!=', $brand->id)->exists()) {
                $code = $originalCode . $counter; 
                $counter++;
            }

            // Simpan kode dan slug baru bersama perubahan nama.
            $data['code'] = $code;
            $data['slug'] = Str::slug($data['name']) . '-' . str_pad($brand->id, 3, '0', STR_PAD_LEFT);
        }

        // Perbarui kolom brand dengan data yang lolos validasi.
        $brand->update($data);

        // Kembali ke daftar brand dengan notifikasi berhasil.
        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Brand berhasil diperbarui.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        // Hapus brand yang ditemukan oleh route model binding.
        $brand->delete();

        // Kembali ke daftar brand dengan notifikasi berhasil.
        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Brand berhasil dihapus.');
    }
}