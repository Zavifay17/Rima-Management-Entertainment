<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Driver;
use App\Models\Pengiriman;
use App\Models\LayananSewa;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan ringkasan statistik & dasbor utama.
     */
    public function index(Request $request)
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

        // 7. Katalog Alat (Top 10)
        $katalogAlat = LayananSewa::take(10)->get();

        // 8. Direktori Pelanggan (10 Terbaru dari Data Order)
        $direktoriPelanggan = Order::select('nama_pelanggan as nama', 'no_hp_pelanggan as no_hp', 'created_at')
                                ->orderBy('created_at', 'desc')
                                ->take(10)
                                ->get();

        // 9. Manajemen Driver (Semua Driver Aktif)
        $manajemenDriver = Driver::all();

        // 10. Data Kalender Logistik
        $year = $request->query('year') ? (int) $request->query('year') : (int) date('Y');
        $month = $request->query('month') ? (int) $request->query('month') : (int) date('n');
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));

        $pengirimansForCalendar = Pengiriman::with(['order', 'driver'])
            ->whereBetween('tgl_jadwal', [$startDate, $endDate])
            ->get();

        $groupedPengirimans = $pengirimansForCalendar->groupBy(function ($item) {
            return $item->tgl_jadwal->format('Y-m-d');
        });

        $ordersForCalendar = Order::where('tgl_mulai', '<=', $endDate)
            ->where('tgl_selesai', '>=', $startDate)
            ->where('status_sewa', '!=', 'Batal')
            ->where('status_sewa', '!=', 'Dibatalkan')
            ->get();

        $groupedOrders = [];
        foreach ($ordersForCalendar as $order) {
            $start = strtotime(max($order->tgl_mulai, $startDate));
            $end = strtotime(min($order->tgl_selesai, $endDate));
            for ($i = $start; $i <= $end; $i += 86400) {
                $dateStr = date('Y-m-d', $i);
                if (!isset($groupedOrders[$dateStr])) {
                    $groupedOrders[$dateStr] = [];
                }
                $groupedOrders[$dateStr][] = $order;
            }
        }

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'activeDriversCount',
            'activeDeliveriesCount',
            'recentOrders',
            'todayDeliveries',
            'katalogAlat',
            'direktoriPelanggan',
            'manajemenDriver',
            'groupedPengirimans',
            'groupedOrders',
            'year',
            'month'
        ));
    }
}
