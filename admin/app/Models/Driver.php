<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'driver';
    protected $primaryKey = 'id_driver';

    protected $fillable = [
        'username',
        'password',
        'nama',
        'no_hp',
        'status_aktif',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function pengirimans()
    {
        return $this->hasMany(Pengiriman::class, 'id_driver', 'id_driver');
    }
}
