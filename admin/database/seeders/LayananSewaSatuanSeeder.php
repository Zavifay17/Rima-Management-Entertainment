<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayananSewaSatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Adding the exact IDs 13 to 22 mapped in BookingController
        $data = [
            [
                'id' => 13,
                'kategori' => 'Panggung',
                'nama_layanan' => 'Mini Panggung (Tanpa Atap)',
                'satuan' => 'm2',
                'harga' => 70000.00,
                'deskripsi' => 'Panggung Podium Modular (Tanpa Atap) ukuran custom per meter, dengan alas karpet halus.',
                'is_paket' => false,
                'id_superadmin' => 1,
            ],
            [
                'id' => 14,
                'kategori' => 'Panggung',
                'nama_layanan' => 'Sewa Balokan Panggung / Rigging',
                'satuan' => 'Balok',
                'harga' => 150000.00,
                'deskripsi' => 'Sewa balokan rigging terpisah untuk berbagai struktur custom. Panjang per balok 3 Meter.',
                'is_paket' => false,
                'id_superadmin' => 1,
            ],
            [
                'id' => 15,
                'kategori' => 'Lighting',
                'nama_layanan' => 'Par LED',
                'satuan' => 'Unit',
                'harga' => 200000.00,
                'deskripsi' => 'Lampu Par LED satuan untuk pewarnaan panggung dan area event.',
                'is_paket' => false,
                'id_superadmin' => 1,
            ],
            [
                'id' => 16,
                'kategori' => 'Lighting',
                'nama_layanan' => 'Beam RDW 230W',
                'satuan' => 'Unit',
                'harga' => 450000.00,
                'deskripsi' => 'Lampu Beam RDW 230W satuan untuk efek cahaya menyorot.',
                'is_paket' => false,
                'id_superadmin' => 1,
            ],
            [
                'id' => 17,
                'kategori' => 'Lighting',
                'nama_layanan' => 'Bola Kaca',
                'satuan' => 'Unit',
                'harga' => 150000.00,
                'deskripsi' => 'Bola Kaca pemantul cahaya satuan.',
                'is_paket' => false,
                'id_superadmin' => 1,
            ],
            [
                'id' => 18,
                'kategori' => 'Lighting',
                'nama_layanan' => 'Lampu Fresnel 300W',
                'satuan' => 'Unit',
                'harga' => 350000.00,
                'deskripsi' => 'Lampu Fresnel 300W satuan untuk penerangan wajah dan subjek panggung.',
                'is_paket' => false,
                'id_superadmin' => 1,
            ],
            [
                'id' => 19,
                'kategori' => 'Lighting',
                'nama_layanan' => 'Lampu Tembak Putih 600W',
                'satuan' => 'Unit',
                'harga' => 150000.00,
                'deskripsi' => 'Lampu Tembak Putih 600W satuan untuk penerangan area umum.',
                'is_paket' => false,
                'id_superadmin' => 1,
            ],
            [
                'id' => 20,
                'kategori' => 'Lighting',
                'nama_layanan' => 'Lampu Tembak Kuning 200W',
                'satuan' => 'Unit',
                'harga' => 150000.00,
                'deskripsi' => 'Lampu Tembak Kuning 200W satuan.',
                'is_paket' => false,
                'id_superadmin' => 1,
            ],
            [
                'id' => 21,
                'kategori' => 'Lighting',
                'nama_layanan' => 'SmokeGun 500W',
                'satuan' => 'Unit',
                'harga' => 450000.00,
                'deskripsi' => 'Mesin asap SmokeGun 500W satuan untuk efek visual panggung.',
                'is_paket' => false,
                'id_superadmin' => 1,
            ],
            [
                'id' => 22,
                'kategori' => 'Lighting',
                'nama_layanan' => 'SmokeGun 300W',
                'satuan' => 'Unit',
                'harga' => 300000.00,
                'deskripsi' => 'Mesin asap SmokeGun 300W satuan.',
                'is_paket' => false,
                'id_superadmin' => 1,
            ]
        ];

        foreach ($data as $item) {
            DB::table('layanan_sewa')->updateOrInsert(['id' => $item['id']], $item);
        }
    }
}
