<?php

// kunci untuk komunikasi beckend route ini hanya tempat ngasih jalan doang
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Login dan pendaftaran pertama hanya tersedia untuk user yang belum login.
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

// Route admin Crud
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

        // URL detail user lama diarahkan ke form edit agar GET /users/{user} tidak 405 error.
        Route::resource('users', UserController::class);

        // Url Crud Category admin
        Route::resource('categories', CategoryController::class);

        // Url Crud Brand admin
        Route::resource('brands', BrandController::class);

        // Url Crud Supplier Admin
        Route::resource('suppliers', SupplierController::class);

        // CRUD produk admin
        Route::resource('products', ProductController::class);

        Route::view('/reports/sales', 'admin.reports.sales')->name('reports.sales');
        Route::view('/reports/inventory', 'admin.reports.inventory')->name('reports.inventory');

    });

// Dashboard role lain tetap menggunakan halaman masing-masing.
Route::view('/gudang/dashboard', 'gudang.dashboard')
    ->middleware(['auth', 'role:warehouse'])
    ->name('gudang.dashboard');

Route::view('/kasir/dashboard', 'kasir.dashboard')
    ->middleware(['auth', 'role:cashier'])
    ->name('kasir.dashboard');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
