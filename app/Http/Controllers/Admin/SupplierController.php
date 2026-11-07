<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    // READ: Menampilkan daftar supplier.
    public function index(Request $request)
    {
        // Ambil kata pencarian dari parameter URL.
        $search = $request->query('search');
        // Pastikan nilai pencarian berupa teks yang sudah dirapikan dan dibatasi.
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';

        // Siapkan query supplier dan tambahkan filter nama jika pencarian diisi.
        $suppliers = Supplier::query()
            ->when($search !== '', function (Builder $query) use ($search) {
                // Cari supplier yang namanya mengandung kata pencarian.
                $query->where(function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
            })
            // Tampilkan supplier terbaru, 10 data per halaman, dan pertahankan query pencarian.
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Kirim data dan kata pencarian ke halaman daftar supplier.
        return view('admin.suppliers.index', [
            'suppliers' => $suppliers,
            'search' => $search,
        ]);
    }

    public function create()
    {
        // Tampilkan form kosong untuk membuat supplier.
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        // Validasi data yang dikirim dari form sebelum disimpan.
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:suppliers,name'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
        ]);

        // Buat record supplier dari data yang sudah lolos validasi.
        Supplier::create($data);

        // Kembali ke daftar supplier dengan notifikasi berhasil.
        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', "Supplier {$data['name']} berhasil ditambahkan.");
    }

    public function edit(Supplier $supplier)
    {
        // Gunakan supplier dari route model binding untuk mengisi form edit.
        return view('admin.suppliers.edit', [
            'supplier' => $supplier,
        ]);
    }

    public function update(Request $request, Supplier $supplier)
    {
        // Validasi nilai baru; field kontak boleh kosong.
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
        ]);

        // Terapkan perubahan pada supplier yang sedang diedit.
        $supplier->update($data);

        // Kembali ke daftar supplier dengan notifikasi berhasil.
        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        // Hapus supplier yang ditemukan dari URL.
        $supplier->delete();

        // Kembali ke daftar supplier dan tampilkan notifikasi berhasil.
        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Supplier Berhasil Dihapus.');
    }
}
