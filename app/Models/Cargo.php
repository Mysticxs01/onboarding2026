<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Area;
use App\Models\User;

class Cargo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'area_id',
    ];

    /* =========================
       Relaciones
       ========================= */

    // Un cargo pertenece a un área
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    // Un cargo tiene muchos usuarios
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
