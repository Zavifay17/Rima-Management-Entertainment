<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $items = [
            ['id' => 13, 'kategori' => 'Panggung', 'nama_layanan' => 'Mini Panggung / Podium', 'satuan' => 'm²', 'harga' => 70000, 'deskripsi' => 'Panggung podium tanpa atap', 'is_paket' => false],
            ['id' => 14, 'kategori' => 'Panggung', 'nama_layanan' => 'Sewa Balokan Panggung / Rigging', 'satuan' => 'balok', 'harga' => 150000, 'deskripsi' => 'Balokan rigging terpisah', 'is_paket' => false],
            ['id' => 15, 'kategori' => 'Lighting', 'nama_layanan' => 'Par LED', 'satuan' => 'unit', 'harga' => 200000, 'deskripsi' => 'Par LED', 'is_paket' => false],
            ['id' => 16, 'kategori' => 'Lighting', 'nama_layanan' => 'Beam RDW 230W', 'satuan' => 'unit', 'harga' => 450000, 'deskripsi' => 'Beam RDW 230W', 'is_paket' => false],
            ['id' => 17, 'kategori' => 'Lighting', 'nama_layanan' => 'Bola Kaca', 'satuan' => 'unit', 'harga' => 150000, 'deskripsi' => 'Bola Kaca', 'is_paket' => false],
            ['id' => 18, 'kategori' => 'Lighting', 'nama_layanan' => 'Lampu Fresnel 300W', 'satuan' => 'unit', 'harga' => 350000, 'deskripsi' => 'Lampu Fresnel 300W', 'is_paket' => false],
            ['id' => 19, 'kategori' => 'Lighting', 'nama_layanan' => 'Lampu Tembak Putih 600W', 'satuan' => 'unit', 'harga' => 150000, 'deskripsi' => 'Lampu Tembak Putih 600W', 'is_paket' => false],
            ['id' => 20, 'kategori' => 'Lighting', 'nama_layanan' => 'Lampu Tembak Kuning 200W', 'satuan' => 'unit', 'harga' => 150000, 'deskripsi' => 'Lampu Tembak Kuning 200W', 'is_paket' => false],
            ['id' => 21, 'kategori' => 'Lighting', 'nama_layanan' => 'SmokeGun 500W', 'satuan' => 'unit', 'harga' => 450000, 'deskripsi' => 'SmokeGun 500W', 'is_paket' => false],
            ['id' => 22, 'kategori' => 'Lighting', 'nama_layanan' => 'SmokeGun 300W', 'satuan' => 'unit', 'harga' => 300000, 'deskripsi' => 'SmokeGun 300W', 'is_paket' => false],
        ];

        foreach ($items as $item) {
            $exists = DB::table('layanan_sewa')->where('id', $item['id'])->exists();
            if (!$exists) {
                DB::table('layanan_sewa')->insert([
                    'id' => $item['id'],
                    'kategori' => $item['kategori'],
                    'nama_layanan' => $item['nama_layanan'],
                    'satuan' => $item['satuan'],
                    'harga' => $item['harga'],
                    'deskripsi' => $item['deskripsi'],
                    'is_paket' => $item['is_paket'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('layanan_sewa')->whereBetween('id', [13, 22])->delete();
    }
};
