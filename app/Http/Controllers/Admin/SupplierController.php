<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SupplierController extends Controller
{
    // READ: Menampilkan seluruh user
    public function index(Request $request)
    {
        $search = $request->query('search');
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';

        $suppliers = Supplier::query()
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.suppliers.index', [
            'suppliers' => $suppliers,
            'search' => $search,
        ]);
    }

    public function create(Request $request)
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:255', 'unique:suppliers,code'],
            'email' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
        ]);

        // LOGIKA MEMBUAT INISIAL (Contoh: "Alat Berat" menjadi "AB")
        // Memecah nama menjadi kata, ambil huruf pertama, gabungkan, lalu ubah ke HURUF BESAR
        $words = explode(' ', $data['name']);
        $initials = '';
        foreach ($words as $word) {
            $initials .= substr($word, 0, 1);
        }
        $prefix = strtoupper($initials); // Hasil: "AB"

        // LOGIKA GENERATE KODE ACAK YANG AMAN (ANTI-KEMBAR)
        do {
            // Membuat 4 karakter alfanumerik acak huruf besar (Contoh: X7R2)
            $randomString = strtoupper(Str::random(4));
            $generatedCode = $prefix.'-'.$randomString; // Hasil: "AB-X7R2"

            // Cek ke database apakah kode ini sudah pernah dipakai
            $codeExists = Supplier::where('code', $generatedCode)->exists();
        } while ($codeExists); // Jika sudah ada, loop akan mengulang generate kode baru

        // Masukkan kode yang sudah pasti unik ke dalam data
        $data['code'] = $generatedCode;

        // 5. Simpan ke Database
        Supplier::create($data);

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', [
            'supplier' => $supplier,
        ]);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
        ]);

        $supplier->update($data);

        return redirect()
            ->route('admin.suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()
            ->route('admin.supplier.index')
            ->with('success', 'Supplier Berhasil Dihapus.');
    }
}
