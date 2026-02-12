<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\Cargo;
use App\Models\User;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /* =========================
       Relaciones
       ========================= */

    public function cargos()
    {
        return $this->hasMany(Cargo::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
