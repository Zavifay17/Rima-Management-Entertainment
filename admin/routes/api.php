<?php

use App\Http\Controllers\API\DriverDeliveryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat meregistrasikan API Routes untuk aplikasi Anda.
| Route ini akan secara otomatis diberikan prefix "/api" dan middleware "api".
|
*/

// API Endpoint untuk Login Driver (Flutter)
Route::post('/driver/login', [DriverDeliveryController::class, 'login']);

// API Endpoint untuk Driver mengambil daftar tugas pengiriman miliknya
Route::get('/driver/{id_driver}/pengiriman', [DriverDeliveryController::class, 'getPengiriman']);

// API Endpoint untuk Driver memperbarui status pengiriman (misalnya ketika selesai mengantar)
Route::post('/pengiriman/{id_pengiriman}/update-status', [DriverDeliveryController::class, 'updateStatus']);
