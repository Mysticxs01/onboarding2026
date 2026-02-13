<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'gerencia_id',
        'nombre',
        'descripcion',
    ];

    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class);
    }

    public function cargos()
    {
        return $this->hasMany(Cargo::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
