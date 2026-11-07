<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View|RedirectResponse
    {
        // Pendaftaran admin pertama hanya tersedia jika belum ada user di database.
        if (User::query()->exists()) {
            // Jika sudah ada user, tutup pendaftaran dan arahkan ke login.
            return redirect()->route('login')->with('status', 'Pendaftaran admin pertama sudah ditutup.');
        }

        // Tampilkan form registrasi untuk membuat admin pertama.
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // Pastikan pendaftaran pertama belum digunakan sebelum memproses input.
        if (User::query()->exists()) {
            return redirect()->route('login')->with('status', 'Pendaftaran admin pertama sudah ditutup.');
        }

        // Validasi data pendaftaran, termasuk persetujuan syarat dan konfirmasi password.
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ]);

        // Periksa ulang sebelum menyimpan agar pendaftaran tertutup jika admin pertama sudah dibuat.
        if (User::query()->exists()) {
            return redirect()->route('login')->with('status', 'Pendaftaran admin pertama sudah ditutup.');
        }

        // Buat instance User baru; password di-hash oleh cast pada model User.
        $user = new User;
        // Isi nama dari data yang sudah lolos validasi.
        $user->name = $validated['name'];
        // Isi email dari data yang sudah lolos validasi.
        $user->email = $validated['email'];
        // Atur password; model akan menyimpannya dalam bentuk hash.
        $user->password = $validated['password'];
        // Registrasi awal selalu membuat akun administrator.
        $user->role = 'admin';
        // Simpan akun admin pertama ke database.
        $user->save();

        // Arahkan ke login dan beri tahu halaman bahwa admin berhasil didaftarkan.
        return redirect()->route('login')->with('admin_registered', true);
    }
}
