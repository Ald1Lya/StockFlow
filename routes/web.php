<?php

// kunci untuk komunikasi beckend route ini hanya tempat ngasih jalan doang
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;

// URL untuk ROLE GUDANG
use App\Http\Controllers\Warehouse\IventoryController;



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

// Semua route admin dibungkus middleware auth + role:admin.
// prefix 'admin' → URL jadi /admin/..., name 'admin.' → nama route jadi admin.*
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

        // Master data user — admin bisa lihat, tambah, edit, hapus user lain.
        Route::resource('users', UserController::class);

        // Master data kategori — CRUD kategori produk.
        Route::resource('categories', CategoryController::class);

        // Master data brand — CRUD brand/merek produk.
        Route::resource('brands', BrandController::class);

        // Master data supplier — CRUD supplier/pemasok.
        Route::resource('suppliers', SupplierController::class);

        // Master data produk — CRUD produk.
        Route::resource('products', ProductController::class);

        // Purchase Order (PO) — hanya admin yang buat. Gudang yang terima barang via ubah status.
        // Alur: admin buat PO (status pending) → staf gudang klik Terima Barang (status received)
        // → saat status berubah ke received, stok produk otomatis bertambah sesuai qty detail PO.
        Route::resource('purchases', PurchaseController::class);

        Route::view('/reports/sales', 'admin.reports.sales')->name('reports.sales');
        Route::view('/reports/inventory', 'admin.reports.inventory')->name('reports.inventory');

    });


Route::middleware(['auth', 'role:warehouse'])
    ->prefix('warehouse')
    ->name('warehouse.')
    ->group(function () {
    
        Route::view('/dashboard', 'warehouse.dashboard')->name('dashboard');
    
         // CRUD Iventory
        Route::resource('iventorys', IventoryController::class);

        

    });






Route::view('/kasir/dashboard', 'kasir.dashboard')
    ->middleware(['auth', 'role:cashier'])
    ->name('kasir.dashboard');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
