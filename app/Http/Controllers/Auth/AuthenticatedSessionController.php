<?php

namespace App\Http\Controllers\Auth;

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
        return view('auth.login', [
            'canRegisterAdmin' => ! User::query()->exists(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'role' => ['required', Rule::in(['admin', 'warehouse', 'cashier'])],
            'remember' => ['nullable', 'boolean'],
        ]);

        // Role yang dipilih dipakai sebagai syarat kredensial, bukan sebagai hak akses dari browser.
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

        $dashboardRoute = match ($validated['role']) {
            'admin' => 'admin.dashboard',
            'warehouse' => 'gudang.dashboard',
            'cashier' => 'kasir.dashboard',
        };

        return redirect()->route($dashboardRoute);
    }

    public function destroy(Request $request): RedirectResponse
    {
        // Logout diikuti invalidasi session agar session lama tidak bisa dipakai kembali.
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
