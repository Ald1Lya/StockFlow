<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // READ: Menampilkan seluruh user
    public function index(Request $request)
    {
        // Ambil role dan teks pencarian dari query URL.
        $role = $request->query('role');
        // Rapikan pencarian, ubah nilai non-string menjadi teks, lalu batasi panjangnya.
        $search = mb_substr(trim((string) $request->query('search', '')), 0, 100);

        // Mulai query daftar user.
        $users = User::query()
            // Terapkan filter role hanya jika nilainya termasuk role yang diizinkan.
            ->when(
                in_array($role, ['admin', 'warehouse', 'cashier'], true),
                fn (Builder $query) => $query->where('role', $role),
            )
            // Tambahkan filter nama atau email jika admin mengisi pencarian.
            ->when($search !== '', function (Builder $query) use ($search) {
                // Kelompokkan pencocokan nama/email agar filter role tetap berlaku.
                $query->where(function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            // Urutkan dari user terbaru dan tampilkan 10 per halaman.
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Render daftar beserta filter yang aktif agar dapat ditampilkan kembali di form.
        return view('admin.users.index', [
            'users' => $users,
            'selectedRole' => in_array($role, ['admin', 'warehouse', 'cashier'], true) ? $role : null,
            'search' => $search,
        ]);
    }

    // CREATE: Menampilkan form tambah user
    public function create()
    {
        // Tampilkan form pembuatan user baru.
        return view('admin.users.create');
    }

    // CREATE: Menyimpan user baru
    public function store(Request $request)
    {
        // Periksa nama, email unik, password yang dikonfirmasi, dan role yang diizinkan.
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'role' => ['required', 'in:admin,warehouse,cashier'],
        ]);

        // Buat user baru; model User menangani hashing password saat disimpan.
        User::create($data);

        // Kembali ke daftar user dengan notifikasi berhasil.
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    // UPDATE: Menampilkan form edit
    public function edit(User $user)
    {
        // Kirim user hasil route model binding agar form menampilkan data saat ini.
        return view('admin.users.edit', compact('user'));
    }

    public function show(User $user): RedirectResponse
    {
        // Arahkan halaman detail standar resource ke halaman edit yang dipakai aplikasi.
        return redirect()->route('admin.users.edit', $user);
    }

    // UPDATE: Memperbarui user
    public function update(Request $request, User $user)
    {
        // Validasi data baru; email harus unik selain milik user yang sedang diedit.
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'unique:users,email,'.$user->id,
            ],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'role' => ['required', 'in:admin,warehouse,cashier'],
        ]);

        // Password kosong berarti admin tidak meminta perubahan password.
        if (empty($data['password'])) {
            unset($data['password']);
        }

        // Simpan hanya perubahan yang sudah lolos validasi.
        $user->update($data);

        // Kembali ke daftar user dengan notifikasi berhasil.
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    // DELETE: Menghapus user
    public function destroy(Request $request, User $user)
    {
        // Tolak penghapusan jika user target adalah akun admin yang sedang login.
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        // Hapus user setelah lolos pemeriksaan akun sendiri.
        $user->delete();

        // Kembali ke daftar user dengan notifikasi berhasil.
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
