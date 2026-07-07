<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_detail';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_order',
        'id_layanan',
        'kuantitas',
        'subtotal',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }

    public function layananSewa()
    {
        return $this->belongsTo(LayananSewa::class, 'id_layanan', 'id');
    }
}
