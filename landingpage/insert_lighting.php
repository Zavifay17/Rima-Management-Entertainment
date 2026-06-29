<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$items = [
    ['kategori' => 'Lighting', 'nama_layanan' => 'Par LED', 'satuan' => 'Unit', 'harga' => 200000, 'deskripsi' => 'Sewa Satuan', 'is_paket' => 0],
    ['kategori' => 'Lighting', 'nama_layanan' => 'Beam RDW 230W', 'satuan' => 'Unit', 'harga' => 450000, 'deskripsi' => 'Sewa Satuan', 'is_paket' => 0],
    ['kategori' => 'Lighting', 'nama_layanan' => 'Bola Kaca', 'satuan' => 'Unit', 'harga' => 150000, 'deskripsi' => 'Sewa Satuan', 'is_paket' => 0],
    ['kategori' => 'Lighting', 'nama_layanan' => 'Lampu Fresnel 300W', 'satuan' => 'Unit', 'harga' => 350000, 'deskripsi' => 'Sewa Satuan', 'is_paket' => 0],
    ['kategori' => 'Lighting', 'nama_layanan' => 'Lampu Tembak Putih 600W', 'satuan' => 'Unit', 'harga' => 150000, 'deskripsi' => 'Sewa Satuan', 'is_paket' => 0],
    ['kategori' => 'Lighting', 'nama_layanan' => 'Lampu Tembak Kuning 200W', 'satuan' => 'Unit', 'harga' => 150000, 'deskripsi' => 'Sewa Satuan', 'is_paket' => 0],
    ['kategori' => 'Lighting', 'nama_layanan' => 'SmokeGun 500W', 'satuan' => 'Unit', 'harga' => 450000, 'deskripsi' => 'Sewa Satuan', 'is_paket' => 0],
    ['kategori' => 'Lighting', 'nama_layanan' => 'SmokeGun 300W', 'satuan' => 'Unit', 'harga' => 300000, 'deskripsi' => 'Sewa Satuan', 'is_paket' => 0]
];
foreach($items as $i) { \DB::table('layanan_sewa')->insert($i); }
echo json_encode(\DB::table('layanan_sewa')->orderBy('id', 'desc')->limit(8)->get());
