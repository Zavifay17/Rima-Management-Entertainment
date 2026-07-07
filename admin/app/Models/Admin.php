<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';

    protected $fillable = [
        'username',
        'password',
        'nama',
        'no_hp',
        'id_superadmin',
    ];

    protected $hidden = [
        'password',
    ];

    public function superadmin()
    {
        return $this->belongsTo(Superadmin::class, 'id_superadmin', 'id_superadmin');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'id_admin', 'id_admin');
    }
}
