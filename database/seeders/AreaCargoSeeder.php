<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\Cargo;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AreaCargoSeeder extends Seeder
{
    public function run()
    {
        $area1 = Area::create(['nombre' => 'Recursos Humanos']);
        $area2 = Area::create(['nombre' => 'Tecnología']);
        $area3 = Area::create(['nombre' => 'Servicios Generales']);
        $area4 = Area::create(['nombre' => 'Formación y Capacitación']);
        $area5 = Area::create(['nombre' => 'Bienes y Servicios']);

        Cargo::create(['nombre' => 'Analista de RRHH', 'area_id' => $area1->id]);
        Cargo::create(['nombre' => 'Desarrollador', 'area_id' => $area2->id]);
        Cargo::create(['nombre' => 'Técnico de Servicios', 'area_id' => $area3->id]);
        Cargo::create(['nombre' => 'Instructor', 'area_id' => $area4->id]);
        Cargo::create(['nombre' => 'Administrador de Inventario', 'area_id' => $area5->id]);

        User::firstOrCreate(
            ['email' => 'jefe.rrhh@example.com'],
            [
                'name' => 'Jefe de RRHH',
                'password' => Hash::make('password'),
                'area_id' => $area1->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'jefe.tecnologia@example.com'],
            [
                'name' => 'Jefe de Tecnología',
                'password' => Hash::make('password'),
                'area_id' => $area2->id,
            ]
        );
    }
}