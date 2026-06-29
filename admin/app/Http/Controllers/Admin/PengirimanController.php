<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Driver;
use App\Models\Pengiriman;
use Illuminate\Http\Request;

class PengirimanController extends Controller
{
    /**
     * Tampilkan list penugasan pengiriman.
     */
    public function index()
    {
        $pengirimans = Pengiriman::with(['order', 'driver'])->latest()->get();
        return view('admin.pengiriman.index', compact('pengirimans'));
    }

    /**
     * Halaman form menugaskan driver ke order tertentu.
     */
    public function create()
    {
        $orders = Order::all();
        
        // Ambil driver yang berstatus aktif
        $drivers = Driver::where('status_aktif', true)->get();

        return view('admin.pengiriman.create', compact('orders', 'drivers'));
    }

    /**
     * Menyimpan data penugasan pengiriman.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_order' => 'required|exists:orders,id_order',
            'id_driver' => 'required|exists:driver,id_driver',
            'tipe_tugas' => 'required|string|in:Antar,Jemput',
            'tgl_jadwal' => 'required|date',
            'catatan_kondisi_alat' => 'nullable|string',
        ]);

        Pengiriman::create([
            'id_order' => $request->id_order,
            'id_driver' => $request->id_driver,
            'tipe_tugas' => $request->tipe_tugas,
            'tgl_jadwal' => $request->tgl_jadwal,
            'status_tugas' => 'pending', // Awal mula tugas berstatus pending
            'catatan_kondisi_alat' => $request->catatan_kondisi_alat,
        ]);

        return redirect()->route('admin.pengiriman.index')
            ->with('success', 'Tugas pengiriman berhasil ditugaskan ke Driver!');
    }

    /**
     * Tampilkan kalender pengiriman driver.
     */
    public function calendar(Request $request)
    {
        $year = (int)$request->input('year', date('Y'));
        $month = (int)$request->input('month', date('n'));

        // Query pengiriman pada bulan tersebut
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));

        $pengirimans = Pengiriman::with(['order', 'driver'])
            ->whereBetween('tgl_jadwal', [$startDate, $endDate])
            ->get();

        // Kelompokkan tugas berdasarkan tanggal pelaksanaan (Y-m-d)
        $groupedPengirimans = $pengirimans->groupBy(function ($item) {
            return $item->tgl_jadwal->format('Y-m-d');
        });

        // Ambil data Order untuk ditampilkan di kalender logistik (Event Books)
        $orders = Order::where('tgl_mulai', '<=', $endDate)
            ->where('tgl_selesai', '>=', $startDate)
            ->where('status_sewa', '!=', 'Batal')
            ->where('status_sewa', '!=', 'Dibatalkan')
            ->get();

        $groupedOrders = [];
        foreach ($orders as $order) {
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

        return view('admin.pengiriman.calendar', compact('groupedPengirimans', 'groupedOrders', 'year', 'month'));
    }
}
