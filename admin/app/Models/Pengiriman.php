<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengiriman';
    protected $primaryKey = 'id_pengiriman';

    protected $fillable = [
        'id_order',
        'id_driver',
        'tipe_tugas',
        'tgl_jadwal',
        'status_tugas',
        'catatan_kondisi_alat',
        'bukti_foto_url',
    ];

    protected $casts = [
        'tgl_jadwal' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'id_driver', 'id_driver');
    }
}
