<?php

use App\Http\Controllers\BookingController;

Route::get('/', function () {
    return view('landing');
});

Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
