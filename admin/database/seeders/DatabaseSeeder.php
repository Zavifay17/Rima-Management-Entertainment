<?php

namespace Database\Seeders;

use App\Models\Superadmin;
use App\Models\Admin;
use App\Models\Driver;
use App\Models\LayananSewa;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Pengiriman;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Superadmin
        $superadmin = Superadmin::create([
            'username' => 'superadmin',
            'password' => Hash::make('password123'),
        ]);

        // 2. Seed Admin
        $admin = Admin::create([
            'username' => 'adminlutfi',
            'password' => Hash::make('admin123'),
            'nama' => 'Luthfi Ramadhan',
            'no_hp' => '081234567890',
            'id_superadmin' => $superadmin->id_superadmin,
        ]);

        // 4. Seed Drivers
        $driver1 = Driver::create([
            'username' => 'driver_slamet',
            'password' => Hash::make('driver123'),
            'nama' => 'Slamet Riyadi',
            'no_hp' => '089876543210',
            'status_aktif' => true,
        ]);

        $driver2 = Driver::create([
            'username' => 'driver_eko',
            'password' => Hash::make('driver123'),
            'nama' => 'Eko Prasetyo',
            'no_hp' => '089512345678',
            'status_aktif' => true,
        ]);

        // 5. Seed Layanan Sewa (Barang / Jasa Sewa Alat Medis)
        $barang1 = LayananSewa::create([
            'kategori' => 'Alat Penunjang Pernafasan',
            'nama_layanan' => 'Ventilator Portable Philips Respironics',
            'satuan' => 'Unit',
            'harga' => 350000.00,
            'deskripsi' => 'Ventilator medis berukuran portable untuk kebutuhan bantuan pernafasan pasien rawat rumah.',
            'is_paket' => false,
            'id_superadmin' => $superadmin->id_superadmin,
        ]);

        $barang2 = LayananSewa::create([
            'kategori' => 'Monitoring Pasien',
            'nama_layanan' => 'Patient Monitor 5 Parameter Mindray',
            'satuan' => 'Unit',
            'harga' => 150000.00,
            'deskripsi' => 'Memantau ECG, NIBP, SpO2, Respirasi, dan Suhu tubuh pasien secara realtime.',
            'is_paket' => false,
            'id_superadmin' => $superadmin->id_superadmin,
        ]);

        $barang3 = LayananSewa::create([
            'kategori' => 'Fasilitas Pasien',
            'nama_layanan' => 'Ranjang Pasien Elektrik 3 Crank',
            'satuan' => 'Unit',
            'harga' => 200000.00,
            'deskripsi' => 'Bed pasien elektrik dengan 3 fungsi pengaturan tinggi/rendah kepala, kaki, dan kasur.',
            'is_paket' => false,
            'id_superadmin' => $superadmin->id_superadmin,
        ]);

        // 5b. Seed Layanan Sewa — Paket Event (Sound, Lighting, Stage)
        // ID 4 — Sound System 5000W
        $soundA = LayananSewa::create([
            'kategori' => 'Sound System',
            'nama_layanan' => 'Sound System Paket 5000W',
            'satuan' => 'Paket',
            'harga' => 2000000.00,
            'deskripsi' => 'Paket sound system 5000 Watt lengkap dengan mixer, speaker aktif, dan kabel.',
            'is_paket' => true,
            'id_superadmin' => $superadmin->id_superadmin,
        ]);

        // ID 5 — Sound System 10000W
        $soundB = LayananSewa::create([
            'kategori' => 'Sound System',
            'nama_layanan' => 'Sound System Paket 10000W',
            'satuan' => 'Paket',
            'harga' => 3000000.00,
            'deskripsi' => 'Paket sound system 10000 Watt untuk acara menengah-besar.',
            'is_paket' => true,
            'id_superadmin' => $superadmin->id_superadmin,
        ]);

        // ID 6 — Sound System 20000W
        $soundC = LayananSewa::create([
            'kategori' => 'Sound System',
            'nama_layanan' => 'Sound System Paket 20000W',
            'satuan' => 'Paket',
            'harga' => 4500000.00,
            'deskripsi' => 'Paket sound system 20000 Watt untuk konser dan event besar.',
            'is_paket' => true,
            'id_superadmin' => $superadmin->id_superadmin,
        ]);

        // ID 7 — Lighting Hemat
        $lightA = LayananSewa::create([
            'kategori' => 'Lighting',
            'nama_layanan' => 'Lighting Paket Hemat',
            'satuan' => 'Paket',
            'harga' => 2000000.00,
            'deskripsi' => 'Paket lighting hemat dengan par LED dan smoke machine.',
            'is_paket' => true,
            'id_superadmin' => $superadmin->id_superadmin,
        ]);

        // ID 8 — Lighting Menengah
        $lightB = LayananSewa::create([
            'kategori' => 'Lighting',
            'nama_layanan' => 'Lighting Paket Menengah',
            'satuan' => 'Paket',
            'harga' => 3000000.00,
            'deskripsi' => 'Paket lighting menengah dengan beam, par LED, dan fresnel.',
            'is_paket' => true,
            'id_superadmin' => $superadmin->id_superadmin,
        ]);

        // ID 9 — Lighting Mewah
        $lightC = LayananSewa::create([
            'kategori' => 'Lighting',
            'nama_layanan' => 'Lighting Paket Mewah',
            'satuan' => 'Paket',
            'harga' => 4500000.00,
            'deskripsi' => 'Paket lighting premium full set untuk panggung besar.',
            'is_paket' => true,
            'id_superadmin' => $superadmin->id_superadmin,
        ]);

        // ID 10 — Panggung 6x5m
        $stageA = LayananSewa::create([
            'kategori' => 'Panggung',
            'nama_layanan' => 'Panggung Modular 6x5m',
            'satuan' => 'Paket',
            'harga' => 3000000.00,
            'deskripsi' => 'Panggung modular ukuran 6x5 meter dengan atap rigging.',
            'is_paket' => true,
            'id_superadmin' => $superadmin->id_superadmin,
        ]);

        // ID 11 — Panggung 8x6m
        $stageB = LayananSewa::create([
            'kategori' => 'Panggung',
            'nama_layanan' => 'Panggung Modular 8x6m',
            'satuan' => 'Paket',
            'harga' => 4500000.00,
            'deskripsi' => 'Panggung modular ukuran 8x6 meter dengan atap rigging.',
            'is_paket' => true,
            'id_superadmin' => $superadmin->id_superadmin,
        ]);

        // ID 12 — Panggung 10x8m
        $stageC = LayananSewa::create([
            'kategori' => 'Panggung',
            'nama_layanan' => 'Panggung Modular 10x8m',
            'satuan' => 'Paket',
            'harga' => 6000000.00,
            'deskripsi' => 'Panggung modular ukuran 10x8 meter dengan atap rigging untuk event besar.',
            'is_paket' => true,
            'id_superadmin' => $superadmin->id_superadmin,
        ]);

    }
}
