<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\PengirimanController;
use App\Http\Controllers\Admin\ManageAdminController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

// Auth Routes (Public)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Booking Submission Route (Public - for Landing Page integration)
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/', function () {
    return redirect()->route('login');
});

// Protected Routes (Required Admin Session)
Route::middleware('admin.auth')->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // Dasbor is shared by both (or could check role inside controller)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });
});

Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Katalog Layanan CRUD
    Route::resource('/layanan', LayananController::class)->names('layanan');

    // Direktori Pelanggan
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');

    // Order Management
    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::get('/order/{id_order}', [OrderController::class, 'show'])->name('order.show');
    Route::post('/order/{id_order}/update-status', [OrderController::class, 'updateStatus'])->name('order.update-status');
    Route::get('/order/{id_order}/wa-template', [OrderController::class, 'getWhatsAppTemplate'])->name('order.wa-template');

    // Driver Management
    Route::get('/driver', [DriverController::class, 'index'])->name('driver.index');
    Route::get('/driver/create', [DriverController::class, 'create'])->name('driver.create');
    Route::post('/driver', [DriverController::class, 'store'])->name('driver.store');
    Route::get('/driver/{id_driver}/edit', [DriverController::class, 'edit'])->name('driver.edit');
    Route::put('/driver/{id_driver}', [DriverController::class, 'update'])->name('driver.update');
    Route::delete('/driver/{id_driver}', [DriverController::class, 'destroy'])->name('driver.destroy');
    Route::post('/driver/{id_driver}/toggle-status', [DriverController::class, 'toggleStatus'])->name('driver.toggle-status');

    // Pengiriman / Kalender
    Route::get('/pengiriman/calendar', [PengirimanController::class, 'calendar'])->name('pengiriman.calendar');
    Route::get('/pengiriman', [PengirimanController::class, 'index'])->name('pengiriman.index');
    Route::get('/pengiriman/create', [PengirimanController::class, 'create'])->name('pengiriman.create');
    Route::post('/pengiriman', [PengirimanController::class, 'store'])->name('pengiriman.store');
    Route::get('/pengiriman/{id}', [PengirimanController::class, 'show'])->name('pengiriman.show');
});

// Superadmin Only Routes
Route::middleware(['admin.auth', 'superadmin.auth'])->prefix('admin')->name('admin.')->group(function () {
    // Manage Admin
    Route::resource('/manage-admin', ManageAdminController::class)->except(['show']);
    
    // Finance / Pendapatan
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    
    // Report Pemesanan
    Route::get('/report/orders', [ReportController::class, 'orders'])->name('report.orders');
});
