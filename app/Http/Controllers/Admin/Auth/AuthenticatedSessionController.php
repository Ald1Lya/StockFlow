<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        // Tampilkan tombol pendaftaran admin hanya jika database belum memiliki user.
        return view('auth.login', [
            'canRegisterAdmin' => ! User::query()->exists(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // Validasi kredensial, role yang diminta, dan pilihan "ingat saya".
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'role' => ['required', Rule::in(['admin', 'warehouse', 'cashier'])],
            'remember' => ['nullable', 'boolean'],
        ]);

        // Cocokkan email, password, dan role; role dari form hanya syarat login, bukan pemberian hak akses.
        if (! Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
        ], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email, password, atau peran yang dipilih tidak sesuai.',
            ]);
        }

        // Regenerasi ID session setelah login untuk mencegah session fixation.
        $request->session()->regenerate();

        // Pilih dashboard tujuan berdasarkan role akun yang berhasil login.
        $dashboardRoute = match ($validated['role']) {
            'admin' => 'admin.dashboard',
            'warehouse' => 'gudang.dashboard',
            'cashier' => 'kasir.dashboard',
        };

        // Arahkan user ke dashboard rolenya.
        return redirect()->route($dashboardRoute);
    }

    public function destroy(Request $request): RedirectResponse
    {
        // Keluar dari akun yang sedang terautentikasi.
        Auth::logout();
        // Batalkan seluruh data session lama.
        $request->session()->invalidate();
        // Buat token CSRF baru untuk request berikutnya.
        $request->session()->regenerateToken();

        // Kembali ke halaman login.
        return redirect()->route('login');
    }
}
