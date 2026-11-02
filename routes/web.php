<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Login dan pendaftaran pertama hanya tersedia untuk pengunjung yang belum login.
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('register.store');
});

// Setiap role hanya bisa membuka dashboard di folder tampilannya sendiri.
Route::view('/admin/dashboard', 'admin.dashboard')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.dashboard');

Route::view('/gudang/dashboard', 'gudang.dashboard')
    ->middleware(['auth', 'role:warehouse'])
    ->name('gudang.dashboard');

Route::view('/kasir/dashboard', 'kasir.dashboard')
    ->middleware(['auth', 'role:cashier'])
    ->name('kasir.dashboard');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
