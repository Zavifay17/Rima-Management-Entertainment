<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananSewa extends Model
{
    use HasFactory;

    protected $table = 'layanan_sewa';
    protected $primaryKey = 'id';

    protected $fillable = [
        'kategori',
        'nama_layanan',
        'satuan',
        'harga',
        'deskripsi',
        'is_paket',
        'id_superadmin',
    ];

    protected $casts = [
        'is_paket' => 'boolean',
        'harga' => 'decimal:2',
    ];

    public function superadmin()
    {
        return $this->belongsTo(Superadmin::class, 'id_superadmin', 'id_superadmin');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'id_layanan', 'id');
    }
}
