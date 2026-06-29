<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Store a newly created order in pre-existing Supabase database tables.
     */
    public function store(Request $request)
    {
        // 1. Validation rules
        $validator = Validator::make($request->all(), [
            'fullName' => 'required|string|max:255',
            'whatsapp' => 'required|regex:/^[0-9+]{8,15}$/',
            'email' => 'required|email|max:255',
            'eventDate' => 'required|date|after:today',
            'duration' => 'required|integer|min:1|max:30',
            'selectedPackages' => 'required|array|min:1',
            'selectedPackages.*' => 'required|string|in:sound-5000w,sound-10000w,sound-20000w,light-hemat,light-menengah,light-mewah,stage-6x5,stage-8x6,stage-10x8,stage-mini,rigging-balokan,light-custom',
            'luasMiniPanggung' => 'nullable|numeric|min:1',
            'qtyRiggingBalokan' => 'nullable|integer|min:1',
            'qty_light_parled' => 'nullable|integer|min:0',
            'qty_light_beam' => 'nullable|integer|min:0',
            'qty_light_bola' => 'nullable|integer|min:0',
            'qty_light_fresnel' => 'nullable|integer|min:0',
            'qty_light_tembakputih' => 'nullable|integer|min:0',
            'qty_light_tembakkuning' => 'nullable|integer|min:0',
            'qty_light_smoke500' => 'nullable|integer|min:0',
            'qty_light_smoke300' => 'nullable|integer|min:0',
            'specialRequests' => 'nullable|string|max:1000',
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

        // 2. Server-side Package Mapping (corresponds exactly to public.layanan_sewa rows in Supabase)
        $prices = [
            'sound-5000w' => ['id' => 28, 'price' => 2000000],
            'sound-10000w' => ['id' => 29, 'price' => 3000000],
            'sound-20000w' => ['id' => 30, 'price' => 4500000],
            'light-hemat' => ['id' => 7, 'price' => 2000000],
            'light-menengah' => ['id' => 8, 'price' => 3000000],
            'light-mewah' => ['id' => 9, 'price' => 4500000],
            'stage-6x5' => ['id' => 31, 'price' => 3000000],
            'stage-8x6' => ['id' => 32, 'price' => 4500000],
            'stage-10x8' => ['id' => 33, 'price' => 6500000],
            'stage-mini' => ['id' => 34, 'price' => 70000, 'is_dynamic' => true],
            'rigging-balokan' => ['id' => 35, 'price' => 150000, 'is_dynamic_qty' => true],
            'light-custom' => ['is_light_custom' => true],
        ];

        $fullName = $request->input('fullName');
        $whatsapp = $request->input('whatsapp');
        $email = $request->input('email');
        $eventDate = $request->input('eventDate');
        $duration = (int) $request->input('duration');
        $specialRequests = $request->input('specialRequests');
        $selectedPackages = $request->input('selectedPackages');
        $luasMiniPanggung = (float) $request->input('luasMiniPanggung', 0);
        $qtyRiggingBalokan = (int) $request->input('qtyRiggingBalokan', 0);
        
        $lightCustomData = [
            ['id' => 36, 'qty' => (int) $request->input('qty_light_parled', 0), 'price' => 200000],
            ['id' => 37, 'qty' => (int) $request->input('qty_light_beam', 0), 'price' => 450000],
            ['id' => 38, 'qty' => (int) $request->input('qty_light_bola', 0), 'price' => 150000],
            ['id' => 39, 'qty' => (int) $request->input('qty_light_fresnel', 0), 'price' => 350000],
            ['id' => 40, 'qty' => (int) $request->input('qty_light_tembakputih', 0), 'price' => 150000],
            ['id' => 41, 'qty' => (int) $request->input('qty_light_tembakkuning', 0), 'price' => 150000],
            ['id' => 42, 'qty' => (int) $request->input('qty_light_smoke500', 0), 'price' => 450000],
            ['id' => 43, 'qty' => (int) $request->input('qty_light_smoke300', 0), 'price' => 300000],
        ];

        // Calculate Subtotal & Total
        $subtotal = 0;
        foreach ($selectedPackages as $pkg) {
            if (isset($prices[$pkg])) {
                if (isset($prices[$pkg]['is_dynamic']) && $prices[$pkg]['is_dynamic']) {
                    $subtotal += max(300000, round($luasMiniPanggung * 70000));
                } else if (isset($prices[$pkg]['is_dynamic_qty']) && $prices[$pkg]['is_dynamic_qty']) {
                    $subtotal += max(1, $qtyRiggingBalokan) * 150000;
                } else if (isset($prices[$pkg]['is_light_custom']) && $prices[$pkg]['is_light_custom']) {
                    foreach ($lightCustomData as $lc) {
                        if ($lc['qty'] > 0) $subtotal += $lc['qty'] * $lc['price'];
                    }
                } else {
                    $subtotal += $prices[$pkg]['price'];
                }
            }
        }

        // Multiplier duration logic
        $multiplier = 1.0;
        if ($duration == 2) {
            $multiplier = 1.5; // 1 + 0.5
        } elseif ($duration >= 3) {
            $multiplier = 1.5 + (($duration - 2) * 0.25);
        }

        $totalPrice = (int) round($subtotal * $multiplier);

        // Calculate dates
        $tglMulai = $eventDate;
        $tglSelesai = date('Y-m-d', strtotime($eventDate . ' + ' . ($duration - 1) . ' days'));

        // Check for double booking (Overlap detection)
        $overlap = DB::table('orders')
            ->where('tgl_mulai', '<=', $tglSelesai)
            ->where('tgl_selesai', '>=', $tglMulai)
            ->where('status_sewa', '!=', 'Batal')
            ->where('status_sewa', '!=', 'Dibatalkan')
            ->exists();

        if ($overlap) {
            return response()->json([
                'message' => 'Tanggal acara tidak tersedia.',
                'errors' => [
                    'eventDate' => ['Peringatan: Tanggal sudah di pesan. Silakan pilih tanggal atau periode waktu yang berbeda.']
                ]
            ], 422);
        }

        // 3. Database Operations under a transaction to guarantee consistency
        try {
            $result = DB::transaction(function () use ($fullName, $whatsapp, $email, $tglMulai, $tglSelesai, $totalPrice, $selectedPackages, $prices, $duration, $multiplier, $luasMiniPanggung, $qtyRiggingBalokan, $lightCustomData) {
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
                    'created_at' => now(),
                    'updated_at' => now(),
                ], 'id_order');

                // Create Order Details in 'order_detail' table
                $insertedDetails = [];
                foreach ($selectedPackages as $pkg) {
                    if (isset($prices[$pkg])) {
                        if (isset($prices[$pkg]['is_light_custom']) && $prices[$pkg]['is_light_custom']) {
                            foreach ($lightCustomData as $lc) {
                                if ($lc['qty'] > 0) {
                                    $itemSubtotal = (int) round($lc['qty'] * $lc['price'] * $multiplier);
                                    DB::table('order_detail')->insert([
                                        'id_order' => $idOrder,
                                        'id_layanan' => $lc['id'],
                                        'kuantitas' => $lc['qty'],
                                        'subtotal' => $itemSubtotal,
                                    ]);
                                    $insertedDetails[] = [
                                        'id_layanan' => $lc['id'],
                                        'kuantitas' => $lc['qty'],
                                        'subtotal' => $itemSubtotal
                                    ];
                                }
                            }
                            continue;
                        }

                        $idLayanan = $prices[$pkg]['id'];
                        
                        $isDynamic = isset($prices[$pkg]['is_dynamic']) && $prices[$pkg]['is_dynamic'];
                        $isDynamicQty = isset($prices[$pkg]['is_dynamic_qty']) && $prices[$pkg]['is_dynamic_qty'];
                        
                        if ($isDynamic) {
                            $itemPrice = max(300000, round($luasMiniPanggung * 70000));
                            $qty = max(1, $luasMiniPanggung);
                        } else if ($isDynamicQty) {
                            $qty = max(1, $qtyRiggingBalokan);
                            $itemPrice = $qty * 150000;
                        } else {
                            $itemPrice = $prices[$pkg]['price'];
                            $qty = 1;
                        }

                        $itemSubtotal = (int) round($itemPrice * $multiplier);

                        DB::table('order_detail')->insert([
                            'id_order' => $idOrder,
                            'id_layanan' => $idLayanan,
                            'kuantitas' => $qty,
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

            // --- INTEGRASI SUPABASE ---
            $supabaseUrl = env('SUPABASE_URL');
            $supabaseKey = env('SUPABASE_KEY');
            
            if ($supabaseUrl && $supabaseKey) {
                try {
                    // Contoh pengiriman data ke table 'orders' di Supabase
                    Http::withHeaders([
                        'apikey' => $supabaseKey,
                        'Authorization' => 'Bearer ' . $supabaseKey,
                        'Content-Type' => 'application/json',
                        'Prefer' => 'return=minimal'
                    ])->post(rtrim($supabaseUrl, '/') . '/rest/v1/orders', [
                        'nama_pelanggan' => $result['order']['nama_pelanggan'],
                        'no_hp_pelanggan' => $result['order']['no_hp_pelanggan'],
                        'email_pelanggan' => $result['order']['email_pelanggan'],
                        'tgl_mulai' => $result['order']['tgl_mulai'],
                        'tgl_selesai' => $result['order']['tgl_selesai'],
                        'total_harga' => $result['order']['total_harga'],
                        'status_sewa' => $result['order']['status_sewa'],
                        'catatan' => $specialRequests
                    ]);
                } catch (\Exception $e) {
                    // Ignore supabase error to not break local order process, 
                    // or log it if necessary
                    \Log::error('Supabase Error: ' . $e->getMessage());
                }
            }

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
