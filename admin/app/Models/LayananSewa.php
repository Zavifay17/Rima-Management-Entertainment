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

    protected $appends = ['is_available'];

    public function getIsAvailableAttribute()
    {
        $today = now()->toDateString();
        
        $isRented = \App\Models\OrderDetail::where('id_layanan', $this->id)
            ->whereHas('order', function($query) use ($today) {
                $query->whereNotIn('status_sewa', ['Batal', 'Dibatalkan', 'Selesai', 'Dikembalikan'])
                      ->whereDate('tgl_mulai', '<=', $today)
                      ->whereDate('tgl_selesai', '>=', $today);
            })->exists();

        return !$isRented;
    }

    public function superadmin()
    {
        return $this->belongsTo(Superadmin::class, 'id_superadmin', 'id_superadmin');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'id_layanan', 'id');
    }
}
