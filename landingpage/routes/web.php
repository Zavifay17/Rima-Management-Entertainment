<?php

use App\Http\Controllers\BookingController;

use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    $bookedDates = [];
    $orders = DB::table('orders')
                ->where('status_sewa', '!=', 'Batal')
                ->where('status_sewa', '!=', 'Dibatalkan')
                ->get();
    foreach($orders as $order) {
        $start = strtotime($order->tgl_mulai);
        $end = strtotime($order->tgl_selesai);
        for ($i = $start; $i <= $end; $i += 86400) {
            $bookedDates[] = date('Y-m-d', $i);
        }
    }
    return view('landing', ['bookedDates' => array_unique($bookedDates)]);
});

Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
