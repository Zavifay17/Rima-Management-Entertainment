<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id_order';

    protected $fillable = [
        'nama_pelanggan',
        'no_hp_pelanggan',
        'email_pelanggan',
        'id_admin',
        'tgl_mulai',
        'tgl_selesai',
        'total_harga',
        'status_sewa',
    ];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
        'total_harga' => 'decimal:2',
    ];

    /**
     * Virtual pelanggan accessor for backward compatibility in controllers/views
     */
    public function getPelangganAttribute()
    {
        return (object)[
            'id_pelanggan' => $this->id_order,
            'nama' => $this->nama_pelanggan,
            'no_hp' => $this->no_hp_pelanggan,
            'username' => $this->email_pelanggan,
        ];
    }

    /**
     * Get 50% Down Payment minimum amount
     */
    public function getDpMinimumAttribute()
    {
        return $this->total_harga * 0.5;
    }

    /**
     * Get remaining balance to be paid
     */
    public function getSisaPembayaranAttribute()
    {
        return $this->total_harga - $this->dp_minimum;
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'id_order', 'id_order');
    }

    public function pengirimans()
    {
        return $this->hasMany(Pengiriman::class, 'id_order', 'id_order');
    }
}
