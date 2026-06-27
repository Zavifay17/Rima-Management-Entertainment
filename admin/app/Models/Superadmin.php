<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Superadmin extends Model
{
    use HasFactory;

    protected $table = 'superadmin';
    protected $primaryKey = 'id_superadmin';

    protected $fillable = [
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function admins()
    {
        return $this->hasMany(Admin::class, 'id_superadmin', 'id_superadmin');
    }

    public function layananSewas()
    {
        return $this->hasMany(LayananSewa::class, 'id_superadmin', 'id_superadmin');
    }
}
