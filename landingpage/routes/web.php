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
    
    $ulasan = [];
    if (Illuminate\Support\Facades\Schema::hasTable('reviews')) {
        $ulasan = DB::table('reviews')
            ->join('orders', 'reviews.id_order', '=', 'orders.id_order')
            ->whereRaw('reviews.is_published = true')
            ->orderByDesc('reviews.created_at')
            ->limit(10)
            ->get(['reviews.*', 'orders.nama_pelanggan', 'orders.email_pelanggan', 'orders.tgl_mulai', 'orders.tgl_selesai', 'orders.lokasi_alamat']);
    }

    return view('landing', [
        'bookedDates' => array_unique($bookedDates),
        'ulasan' => $ulasan
    ]);
});

Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

Route::get('/ulasan/{id}', function ($id) {
    if (!Illuminate\Support\Facades\Schema::hasTable('reviews')) {
        return redirect('/')->with('error', 'Sistem ulasan sedang dalam pemeliharaan (Deploy Database).');
    }

    $order = DB::table('orders')->where('id_order', $id)->first();
    if (!$order || $order->status_sewa != 'Selesai') {
        return redirect('/')->with('error', 'Pesanan tidak ditemukan atau belum berstatus Selesai.');
    }
    
    $existing = DB::table('reviews')->where('id_order', $id)->first();
    if ($existing) {
        return redirect('/')->with('error', 'Ulasan untuk pesanan ini sudah pernah dikirimkan. Terima kasih!');
    }

    return view('ulasan_form', ['order' => $order]);
});

Route::post('/ulasan/{id}', function (Illuminate\Http\Request $request, $id) {
    if (!Illuminate\Support\Facades\Schema::hasTable('reviews')) {
        return redirect('/')->with('error', 'Sistem ulasan sedang dalam pemeliharaan.');
    }

    $order = DB::table('orders')->where('id_order', $id)->first();
    if (!$order || $order->status_sewa != 'Selesai') {
        return redirect('/')->with('error', 'Pesanan tidak valid.');
    }
    
    DB::table('reviews')->insert([
        'id_order' => $id,
        'rating' => $request->input('rating', 5),
        'comment' => $request->input('comment', ''),
        'is_published' => 'true',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect('/#testimonials')->with('success', 'Ulasan berhasil dikirimkan. Terima kasih atas kepercayaan Anda!');
});
