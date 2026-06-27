<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Driver;
use App\Models\Pengiriman;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tampilkan ringkasan statistik & dasbor utama.
     */
    public function index()
    {
        // 1. Total Pendapatan (dari order yang disetujui atau selesai)
        $totalRevenue = Order::whereIn(DB::raw('lower(status_sewa)'), ['disetujui', 'selesai'])->sum('total_harga');

        // 2. Total Pemesanan masuk
        $totalOrders = Order::count();

        // 3. Jumlah Driver Aktif
        $activeDriversCount = Driver::where('status_aktif', true)->count();

        // 4. Jumlah Pengiriman Aktif (pending/proses)
        $activeDeliveriesCount = Pengiriman::whereIn(DB::raw('lower(status_tugas)'), ['pending', 'proses'])->count();

        // 5. Daftar Pemesanan Terbaru (5 order terakhir)
        $recentOrders = Order::orderBy('created_at', 'desc')->take(5)->get();

        // 6. Jadwal Pengiriman Hari Ini
        $todayDeliveries = Pengiriman::with(['order', 'driver'])
            ->whereDate('tgl_jadwal', today())
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'activeDriversCount',
            'activeDeliveriesCount',
            'recentOrders',
            'todayDeliveries'
        ));
    }
}
