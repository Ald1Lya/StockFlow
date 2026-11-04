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
        // Pendaftaran hanya dibuka sebelum ada akun pertama di database.
        if (User::query()->exists()) {
            return redirect()->route('login')->with('status', 'Pendaftaran admin pertama sudah ditutup.');
        }

        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        if (User::query()->exists()) {
            return redirect()->route('login')->with('status', 'Pendaftaran admin pertama sudah ditutup.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ]);

        // Cek lagi sebelum menyimpan agar akun lain yang baru dibuat menutup pendaftaran.
        if (User::query()->exists()) {
            return redirect()->route('login')->with('status', 'Pendaftaran admin pertama sudah ditutup.');
        }

        $user = new User;
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = $validated['password'];
        $user->role = 'admin';
        $user->save();

        return redirect()->route('login')->with('admin_registered', true);
    }
}
