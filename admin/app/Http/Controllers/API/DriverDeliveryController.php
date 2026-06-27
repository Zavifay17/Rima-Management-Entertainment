<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverDeliveryController extends Controller
{
    /**
     * Autentikasi/Login Driver untuk Aplikasi Flutter.
     * Endpoint: POST /api/driver/login
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $driver = Driver::where('username', $request->username)->first();

        if (!$driver || !Hash::check($request->password, $driver->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah.',
                'data' => null
            ], 401);
        }

        if (!$driver->status_aktif) {
            return response()->json([
                'success' => false,
                'message' => 'Akun driver Anda dinonaktifkan oleh administrator.',
                'data' => null
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login driver berhasil.',
            'driver' => [
                'id_driver' => $driver->id_driver,
                'nama' => $driver->nama,
                'username' => $driver->username,
                'no_hp' => $driver->no_hp,
                'status_aktif' => $driver->status_aktif,
            ]
        ], 200);
    }
    /**
     * Mengambil daftar tugas pengiriman milik Driver tertentu (JSON).
     * Endpoint: GET /api/driver/{id_driver}/pengiriman
     */
    public function getPengiriman($id_driver)
    {
        // Pastikan driver terdaftar di database
        $driver = Driver::find($id_driver);
        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => "Driver dengan ID {$id_driver} tidak ditemukan.",
                'data' => null
            ], 404);
        }

        // Ambil data pengiriman beserta relasi Order, OrderDetail, dan LayananSewa
        $pengirimans = Pengiriman::with([
            'order.orderDetails.layananSewa'
        ])
        ->where('id_driver', $id_driver)
        ->orderBy('id_pengiriman', 'desc')
        ->get();

        // Transformasi struktur agar sangat nyaman di-consume di model Flutter (Clean Architecture)
        $formattedData = $pengirimans->map(function ($pengiriman) {
            $items = $pengiriman->order->orderDetails->map(function ($detail) {
                return [
                    'id_detail' => $detail->id_detail,
                    'id_layanan' => $detail->id_layanan,
                    'nama_barang' => $detail->layananSewa->nama_layanan,
                    'kategori' => $detail->layananSewa->kategori,
                    'satuan' => $detail->layananSewa->satuan,
                    'harga_satuan' => (float) $detail->layananSewa->harga,
                    'kuantitas' => $detail->kuantitas,
                    'subtotal' => (float) $detail->subtotal,
                ];
            });

            return [
                'id_pengiriman' => $pengiriman->id_pengiriman,
                'tipe_tugas' => $pengiriman->tipe_tugas,
                'tgl_jadwal' => $pengiriman->tgl_jadwal->format('Y-m-d'),
                'status_tugas' => $pengiriman->status_tugas,
                'catatan_kondisi_alat' => $pengiriman->catatan_kondisi_alat,
                'bukti_foto_url' => $pengiriman->bukti_foto_url,
                'created_at' => $pengiriman->created_at ? $pengiriman->created_at->toIso8601String() : null,
                'order' => [
                    'id_order' => $pengiriman->order->id_order,
                    'tgl_mulai' => $pengiriman->order->tgl_mulai->format('Y-m-d'),
                    'tgl_selesai' => $pengiriman->order->tgl_selesai->format('Y-m-d'),
                    'total_harga' => (float) $pengiriman->order->total_harga,
                    'status_sewa' => $pengiriman->order->status_sewa,
                    'pelanggan' => [
                        'id_pelanggan' => $pengiriman->order->pelanggan->id_pelanggan,
                        'nama' => $pengiriman->order->pelanggan->nama,
                        'no_hp' => $pengiriman->order->pelanggan->no_hp,
                    ],
                    'items' => $items
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Data pengiriman untuk Driver berhasil diambil.',
            'driver' => [
                'id_driver' => $driver->id_driver,
                'nama' => $driver->nama,
                'no_hp' => $driver->no_hp,
                'status_aktif' => $driver->status_aktif,
            ],
            'count' => $formattedData->count(),
            'data' => $formattedData
        ], 200);
    }

    /**
     * Memperbarui status pengiriman oleh Driver.
     * Endpoint: POST /api/pengiriman/{id_pengiriman}/update-status
     */
    public function updateStatus(Request $request, $id_pengiriman)
    {
        $pengiriman = Pengiriman::find($id_pengiriman);
        if (!$pengiriman) {
            return response()->json([
                'success' => false,
                'message' => "Data pengiriman dengan ID {$id_pengiriman} tidak ditemukan.",
                'data' => null
            ], 404);
        }

        // Validasi input dari Flutter
        $request->validate([
            'status_tugas' => 'required|string|in:pending,proses,selesai,accepted,pickup,on_the_way,arrived,done,cancelled',
            'catatan_kondisi_alat' => 'nullable|string',
            'bukti_foto_url' => 'nullable|string'
        ]);

        // Perbarui data tugas
        $pengiriman->update([
            'status_tugas' => $request->status_tugas,
            'catatan_kondisi_alat' => $request->catatan_kondisi_alat ?? $pengiriman->catatan_kondisi_alat,
            'bukti_foto_url' => $request->bukti_foto_url ?? $pengiriman->bukti_foto_url,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pengiriman berhasil diperbarui.',
            'data' => [
                'id_pengiriman' => $pengiriman->id_pengiriman,
                'status_tugas' => $pengiriman->status_tugas,
                'catatan_kondisi_alat' => $pengiriman->catatan_kondisi_alat,
                'bukti_foto_url' => $pengiriman->bukti_foto_url,
            ]
        ], 200);
    }
}
