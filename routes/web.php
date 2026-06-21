<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboard;
use App\Http\Controllers\Dapur\DashboardController as DapurDashboard;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Dapur\DapurController;
use App\Http\Controllers\Kasir\DashboardController;
use App\Http\Controllers\Admin\SettingController;

// ── 1. Redirect Root ──────────────────────────────
Route::get('/', function () {
    return redirect()->route('login');
});

// ── 2. Auth Routes ────────────────────────────────
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── 3. Admin Routes (Middleware: role:admin) ──────
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');

    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('admin.settings.update');



    // Kategori & Menu
    Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit'])->names('admin.categories');
    Route::resource('menus', MenuController::class)->except(['show', 'create', 'edit'])->names('admin.menus');
    Route::patch('/menus/{menu}/toggle-status', [MenuController::class, 'toggleStatus'])->name('admin.menus.toggle-status');

    // User & Table
    Route::resource('users', UserController::class)->except(['show', 'create', 'edit'])->names('admin.users');
    Route::resource('tables', TableController::class)->except([ 'create', 'edit'])->names('admin.tables');
    Route::post('/tables/{table}/regenerate-qr', [TableController::class, 'regenerateQr'])->name('admin.tables.regenerate-qr');
    Route::get('/tables/{id}',[TableController::class,'show'])->name('admin.table.show');
});

// ── 4. Kasir Routes (Middleware: role:kasir) ──────
Route::prefix('kasir')->middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/dashboard', [KasirDashboard::class, 'index'])->name('kasir.dashboard');
    Route::get('/pesanan-baru', [KasirDashboard::class, 'createOrder'])->name('kasir.pesanan-baru');
    Route::post('/simpan-pesanan', [KasirDashboard::class, 'storeOrder'])->name('kasir.store-order');
    Route::get('/riwayat-pembayaran', [KasirDashboard::class, 'history'])->name('kasir.pembayaran.index');
    Route::get('/proses-bayar/{id}', [KasirDashboard::class, 'showPembayaran'])->name('kasir.pembayaran.show');
    Route::post('/konfirmasi-pembayaran/{id}', [KasirDashboard::class, 'prosesBayar'])->name('kasir.pembayaran.konfirmasi');
    Route::get('/cetak-struk/{id}', [KasirDashboard::class, 'printStruk'])->name('kasir.print-struk');
    Route::patch('/meja/{id}/kosongkan', [KasirDashboard::class, 'resetMeja'])->name('kasir.meja.kosongkan');
    Route::get('/laporan-penjualan', [App\Http\Controllers\Kasir\DashboardController::class, 'report'])->name('kasir.report');
    Route::get('/laporan-penjualan/pdf', [App\Http\Controllers\Kasir\DashboardController::class, 'exportPdf'])->name('kasir.report.pdf');



});

// ── 5. Dapur Routes (Middleware: role:dapur) ──────
    Route::prefix('dapur')->middleware(['auth', 'role:dapur'])->group(function () {
    Route::get('/dashboard', [DapurController::class, 'index'])->name('dapur.dashboard');
    Route::get('/history', [DapurController::class, 'history'])->name('dapur.history');
    Route::post('/update-status/{id}', [DapurController::class, 'updateStatus'])->name('dapur.update-status');
    Route::get('/check-new', [DapurController::class, 'checkNewOrders'])->name('dapur.check-new');
    Route::get('/menus', [DapurController::class, 'menus'])->name('dapur.menus');
    Route::patch('/menus/{id}/toggle-status', [DapurController::class, 'toggleMenuStatus'])->name('dapur.menus.toggle-status');
});

// ── 6. Customer & Order Routes ────────────────────
Route::prefix('order')->group(function () {

    Route::get('/success', function () {return view('customer.success');})->name('order.success');
    Route::get('/{number}', [CustomerController::class, 'welcome'])->name('order.welcome');
    Route::get('/{number}/menu', [CustomerController::class, 'index'])->name('order.menu');
    // Manajemen Keranjang
    Route::get('/cart/view', [CustomerController::class, 'viewCart'])->name('order.cart');
    Route::post('/cart/add', [CustomerController::class, 'addToCart'])->name('order.add-to-cart');
    Route::post('/cart/update', [CustomerController::class, 'updateCart'])->name('order.update-cart');
    Route::post('/cart/remove', [CustomerController::class, 'removeFromCart'])->name('order.remove-from-cart');
    // Proses Checkout
    Route::post('/checkout', [CustomerController::class, 'checkout'])->name('order.checkout');

});
