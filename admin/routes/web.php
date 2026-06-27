<?php

use App\Http\Controllers\BookingController;

use App\Models\Pelanggan;
use App\Models\Pengiriman;
use App\Models\Driver;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\PengirimanController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\PelangganController;

// Auth Routes (Public)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Booking Submission Route (Public - for Landing Page integration)
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

// Protected Routes (Required Admin Session)
Route::middleware('admin.auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    // Admin Dashboard Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

        Route::get('/pengiriman/calendar', [PengirimanController::class, 'calendar'])->name('pengiriman.calendar');
        Route::get('/pengiriman', [PengirimanController::class, 'index'])->name('pengiriman.index');
        Route::get('/pengiriman/create', [PengirimanController::class, 'create'])->name('pengiriman.create');
        Route::post('/pengiriman', [PengirimanController::class, 'store'])->name('pengiriman.store');
    });
});

