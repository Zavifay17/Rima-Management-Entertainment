<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index(Request $request){
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
    }


    /**
     * Store a newly created order in pre-existing Supabase database tables.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // 1. Validation rules
        $validator = Validator::make($request->all(), [
            'fullName' => 'required|string|max:255',
            'whatsapp' => 'required|regex:/^[0-9+]{8,15}$/',
            'email' => 'required|email|max:255',
            'eventDate' => 'required|date|after:today',
            'duration' => 'required|integer|min:1|max:30',
            'selectedPackages' => 'required|array|min:1',
            'selectedPackages.*' => 'required|string|in:sound-5000w,sound-10000w,sound-20000w,light-hemat,light-menengah,light-mewah,stage-6x5,stage-8x6,stage-10x8',
            'specialRequests' => 'nullable|string|max:1000',
            'lokasi_lat' => 'nullable|numeric|between:-90,90',
            'lokasi_lng' => 'nullable|numeric|between:-180,180',
            'lokasi_alamat' => 'nullable|string|max:500',
        ], [
            'fullName.required' => 'Nama lengkap atau nama instansi wajib diisi.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.regex' => 'Format nomor WhatsApp tidak valid (8-15 digit angka).',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'eventDate.required' => 'Tanggal acara wajib dipilih.',
            'eventDate.after' => 'Tanggal acara harus di masa depan (mulai besok).',
            'duration.required' => 'Durasi sewa wajib ditentukan.',
            'duration.min' => 'Durasi sewa minimal 1 hari.',
            'selectedPackages.required' => 'Harap pilih minimal satu paket penyewaan.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Server-side Package Mapping (corresponds to layanan_sewa rows seeded in DatabaseSeeder)
        $prices = [
            'sound-5000w' => ['id' => 4, 'price' => 2000000],
            'sound-10000w' => ['id' => 5, 'price' => 3000000],
            'sound-20000w' => ['id' => 6, 'price' => 4500000],
            'light-hemat' => ['id' => 7, 'price' => 2000000],
            'light-menengah' => ['id' => 8, 'price' => 3000000],
            'light-mewah' => ['id' => 9, 'price' => 4500000],
            'stage-6x5' => ['id' => 10, 'price' => 3000000],
            'stage-8x6' => ['id' => 11, 'price' => 4500000],
            'stage-10x8' => ['id' => 12, 'price' => 6000000],
        ];

        $fullName = $request->input('fullName');
        $whatsapp = $request->input('whatsapp');
        $email = $request->input('email');
        $eventDate = $request->input('eventDate');
        $duration = (int) $request->input('duration');
        $specialRequests = $request->input('specialRequests');
        $selectedPackages = $request->input('selectedPackages');

        // Calculate Subtotal & Total
        $subtotal = 0;
        foreach ($selectedPackages as $pkg) {
            if (isset($prices[$pkg])) {
                $subtotal += $prices[$pkg]['price'];
            }
        }

        // Discount logic based on duration
        $discount = 1.0;
        if ($duration >= 3 && $duration < 7) {
            $discount = 0.95; // 5% discount
        } elseif ($duration >= 7) {
            $discount = 0.90; // 10% discount
        }

        $totalPrice = (int) round(($subtotal * $duration) * $discount);

        // Calculate dates
        $tglMulai = $eventDate;
        $tglSelesai = date('Y-m-d', strtotime($eventDate . ' + ' . ($duration - 1) . ' days'));

        // 3. Database Operations under a transaction to guarantee consistency
        try {
            $result = DB::transaction(function () use ($fullName, $whatsapp, $email, $tglMulai, $tglSelesai, $totalPrice, $selectedPackages, $prices, $duration, $discount, $request) {
                // Create Order in 'orders' table
                $idOrder = DB::table('orders')->insertGetId([
                    'nama_pelanggan' => $fullName,
                    'no_hp_pelanggan' => $whatsapp,
                    'email_pelanggan' => $email,
                    'id_admin' => null, // No admin assigned yet
                    'tgl_mulai' => $tglMulai,
                    'tgl_selesai' => $tglSelesai,
                    'total_harga' => $totalPrice,
                    'status_sewa' => 'Pending', // Default rental status
                    'lokasi_lat' => $request->input('lokasi_lat'),
                    'lokasi_lng' => $request->input('lokasi_lng'),
                    'lokasi_alamat' => $request->input('lokasi_alamat'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ], 'id_order');

                // Create Order Details in 'order_detail' table
                $insertedDetails = [];
                foreach ($selectedPackages as $pkg) {
                    if (isset($prices[$pkg])) {
                        $idLayanan = $prices[$pkg]['id'];
                        $itemPrice = $prices[$pkg]['price'];
                        // Calculate total subtotal for this item with duration and discount
                        $itemSubtotal = (int) round(($itemPrice * $duration) * $discount);

                        DB::table('order_detail')->insert([
                            'id_order' => $idOrder,
                            'id_layanan' => $idLayanan,
                            'kuantitas' => 1,
                            'subtotal' => $itemSubtotal,
                        ]);

                        $insertedDetails[] = [
                            'id_layanan' => $idLayanan,
                            'kuantitas' => 1,
                            'subtotal' => $itemSubtotal
                        ];
                    }
                }

                return [
                    'customer' => [
                        'id_pelanggan' => $idOrder,
                        'nama' => $fullName,
                        'no_hp' => $whatsapp,
                    ],
                    'order' => [
                        'id_order' => $idOrder,
                        'nama_pelanggan' => $fullName,
                        'no_hp_pelanggan' => $whatsapp,
                        'email_pelanggan' => $email,
                        'id_admin' => null,
                        'tgl_mulai' => $tglMulai,
                        'tgl_selesai' => $tglSelesai,
                        'total_harga' => $totalPrice,
                        'status_sewa' => 'Pending',
                    ],
                    'details' => $insertedDetails
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Pemesanan berhasil didaftarkan di sistem!',
                'customer' => $result['customer'],
                'order' => $result['order'],
                'details' => $result['details']
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat memproses pemesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}
