<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Tampilkan daftar seluruh order.
     */
    public function index()
    {
        $orders = Order::with(['orderDetails.layananSewa'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.order.index', compact('orders'));
    }

    /**
     * Tampilkan detail pesanan tunggal.
     */
    public function show($id_order)
    {
        $order = Order::with(['orderDetails.layananSewa', 'pengirimans.driver'])
            ->findOrFail($id_order);

        return view('admin.order.show', compact('order'));
    }

    /**
     * Memperbarui status pemesanan.
     */
    public function updateStatus(Request $request, $id_order)
    {
        $order = Order::findOrFail($id_order);

        $request->validate([
            'status_sewa' => 'required|string|in:Pending,pending,Disetujui,disetujui,Diproses,diproses,Selesai,selesai,Dibatalkan,dibatalkan',
        ]);

        // Tetapkan nilai status sesuai input (biarkan case-sensitive seperti di seeder/booking controller)
        $order->status_sewa = $request->status_sewa;
        $order->save();

        return redirect()->route('admin.order.show', $order->id_order)
            ->with('success', 'Status sewa pemesanan berhasil diperbarui!');
    }

    /**
     * Mendapatkan template pesan konfirmasi WhatsApp untuk pemesanan ini.
     */
    public function getWhatsAppTemplate($id_order)
    {
        $order = Order::with(['orderDetails.layananSewa'])->findOrFail($id_order);
        $pelanggan = $order->pelanggan;

        // Susun daftar barang/paket sewa
        $itemDetails = "";
        foreach ($order->orderDetails as $detail) {
            $namaLayanan = $detail->layananSewa ? $detail->layananSewa->nama_layanan : 'Paket Sewa';
            $itemDetails .= "- {$namaLayanan} (x{$detail->kuantitas})\n";
        }

        // Hitung durasi hari
        $tglMulai = \Carbon\Carbon::parse($order->tgl_mulai);
        $tglSelesai = \Carbon\Carbon::parse($order->tgl_selesai);
        $durasi = $tglMulai->diffInDays($tglSelesai) + 1;

        $message = "Halo *{$pelanggan->nama}*,\n\nTerima kasih telah memesan sewa alat event di *RME Logistics*. Berikut rincian pemesanan Anda:\n\n";
        $message .= "*Order ID*: #{$order->id_order}\n";
        $message .= "*Tanggal*: {$tglMulai->format('d M Y')} s/d {$tglSelesai->format('d M Y')} ({$durasi} Hari)\n";
        $message .= "*Rincian Barang*:\n{$itemDetails}";
        $message .= "*Total Tagihan*: Rp " . number_format($order->total_harga, 0, ',', '.') . "\n";
        $message .= "*Uang Muka (DP 50%)*: Rp " . number_format($order->dp_minimum, 0, ',', '.') . "\n";
        $message .= "*Sisa Pelunasan*: Rp " . number_format($order->sisa_pembayaran, 0, ',', '.') . "\n\n";
        $message .= "Untuk memproses pesanan Anda, mohon melakukan transfer uang muka sebesar **DP 50% yaitu Rp " . number_format($order->dp_minimum, 0, ',', '.') . "** ke rekening berikut:\n";
        $message .= "*Bank Mandiri: 123-4567-890 a/n RME Logistics*\n\n";
        $message .= "Mohon kirimkan bukti transfer DP Anda di sini untuk konfirmasi. Terima kasih!";

        // Sanitasi Nomor HP
        $phone = preg_replace('/[^0-9]/', '', $pelanggan->no_hp);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $link = "https://wa.me/{$phone}?text=" . urlencode($message);

        return response()->json([
            'id_order' => $order->id_order,
            'nama_pelanggan' => $pelanggan->nama,
            'no_hp' => $pelanggan->no_hp,
            'formatted_no_hp' => $phone,
            'pesan' => $message,
            'whatsapp_link' => $link
        ]);
    }
}
