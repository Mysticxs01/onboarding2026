<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gerencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'activo',
    ];

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function cargos()
    {
        return $this->hasMany(Cargo::class);
    }
}
