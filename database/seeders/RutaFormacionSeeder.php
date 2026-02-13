<?php

namespace Database\Seeders;

use App\Models\RutaFormacion;
use App\Models\Cargo;
use App\Models\Curso;
use App\Models\Area;
use Illuminate\Database\Seeder;

class RutaFormacionSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener cargos clave
        $cargoAnalista = Cargo::where('nombre', 'Analista Operativo')->first();
        $cargoSupervisor = Cargo::where('nombre', 'Supervisor de Área')->first();
        $cargoAsistente = Cargo::where('nombre', 'Asistente Administrativo')->first();
        $cargoJefe = Cargo::where('nombre', 'Jefe de Área')->first();

        // Obtener cursos
        $cursoInduccion = Curso::where('codigo', 'IND-001')->first();
        $cursoPolicias = Curso::where('codigo', 'POL-001')->first();
        $cursoSST = Curso::where('codigo', 'SST-001')->first();
        $cursoSARLAFT = Curso::where('codigo', 'SAR-001')->first();
        $cursoLiderazgo = Curso::where('codigo', 'LID-001')->first();
        $cursoComunicacion = Curso::where('codigo', 'COM-001')->first();
        $cursoATC = Curso::where('codigo', 'ATC-001')->first();
        $cursoOffice = Curso::where('codigo', 'TEC-001')->first();
        $cursoRiesgos = Curso::where('codigo', 'RIE-001')->first();
        $cursoCiber = Curso::where('codigo', 'CIBE-001')->first();
        $cursoProductos = Curso::where('codigo', 'PROD-001')->first();
        $cursoNegociacion = Curso::where('codigo', 'NEG-001')->first();
        $cursoNIIF = Curso::where('codigo', 'NIIF-001')->first();

        // Ruta para Analista Operativo
        if ($cargoAnalista && $cursoInduccion) {
            $ruta = RutaFormacion::create([
                'nombre' => 'Ruta Analista Operativo',
                'descripcion' => 'Formación requerida para analistas operativos',
                'cargo_id' => $cargoAnalista->id,
                'version' => '1.0',
                'fecha_vigencia' => now()->addYear(),
                'activa' => true,
            ]);

            $cursos = [
                [$cursoInduccion->id, 0, true],
                [$cursoPolicias->id, 1, true],
                [$cursoSST->id, 2, true],
                [$cursoSARLAFT->id, 3, true],
                [$cursoCiber->id, 4, true],
                [$cursoOffice->id, 5, false],
                [$cursoProductos->id, 6, false],
            ];

            foreach ($cursos as [$cursoId, $secuencia, $obligatorio]) {
                $ruta->cursos()->attach($cursoId, [
                    'numero_secuencia' => $secuencia,
                    'es_obligatorio' => $obligatorio,
                ]);
            }

            $ruta->duracion_total_horas = $ruta->calcularDuracionTotal();
            $ruta->save();
        }

        // Ruta para Supervisor de Área
        if ($cargoSupervisor && $cursoInduccion) {
            $ruta = RutaFormacion::create([
                'nombre' => 'Ruta Supervisor de Área',
                'descripcion' => 'Formación requerida para supervisores',
                'cargo_id' => $cargoSupervisor->id,
                'version' => '1.0',
                'fecha_vigencia' => now()->addYear(),
                'activa' => true,
            ]);

            $cursos = [
                [$cursoInduccion->id, 0, true],
                [$cursoPolicias->id, 1, true],
                [$cursoSST->id, 2, true],
                [$cursoSARLAFT->id, 3, true],
                [$cursoCiber->id, 4, true],
                [$cursoLiderazgo->id, 5, true],
                [$cursoComunicacion->id, 6, true],
                [$cursoRiesgos->id, 7, false],
                [$cursoNegociacion->id, 8, false],
            ];

            foreach ($cursos as [$cursoId, $secuencia, $obligatorio]) {
                $ruta->cursos()->attach($cursoId, [
                    'numero_secuencia' => $secuencia,
                    'es_obligatorio' => $obligatorio,
                ]);
            }

            $ruta->duracion_total_horas = $ruta->calcularDuracionTotal();
            $ruta->save();
        }

        // Ruta para Asistente Administrativo
        if ($cargoAsistente && $cursoInduccion) {
            $ruta = RutaFormacion::create([
                'nombre' => 'Ruta Asistente Administrativo',
                'descripcion' => 'Formación requerida para asistentes',
                'cargo_id' => $cargoAsistente->id,
                'version' => '1.0',
                'fecha_vigencia' => now()->addYear(),
                'activa' => true,
            ]);

            $cursos = [
                [$cursoInduccion->id, 0, true],
                [$cursoPolicias->id, 1, true],
                [$cursoSST->id, 2, true],
                [$cursoSARLAFT->id, 3, true],
                [$cursoCiber->id, 4, true],
                [$cursoOffice->id, 5, false],
                [$cursoComunicacion->id, 6, false],
            ];

            foreach ($cursos as [$cursoId, $secuencia, $obligatorio]) {
                $ruta->cursos()->attach($cursoId, [
                    'numero_secuencia' => $secuencia,
                    'es_obligatorio' => $obligatorio,
                ]);
            }

            $ruta->duracion_total_horas = $ruta->calcularDuracionTotal();
            $ruta->save();
        }

        // Ruta para Jefe de Área
        if ($cargoJefe && $cursoInduccion) {
            $ruta = RutaFormacion::create([
                'nombre' => 'Ruta Jefe de Área',
                'descripcion' => 'Formación requerida para jefes de área',
                'cargo_id' => $cargoJefe->id,
                'version' => '1.0',
                'fecha_vigencia' => now()->addYear(),
                'activa' => true,
            ]);

            $cursos = [
                [$cursoInduccion->id, 0, true],
                [$cursoPolicias->id, 1, true],
                [$cursoSST->id, 2, true],
                [$cursoSARLAFT->id, 3, true],
                [$cursoCiber->id, 4, true],
                [$cursoLiderazgo->id, 5, true],
                [$cursoComunicacion->id, 6, true],
                [$cursoRiesgos->id, 7, true],
                [$cursoNegociacion->id, 8, true],
                [$cursoNIIF->id, 9, false],
            ];

            foreach ($cursos as [$cursoId, $secuencia, $obligatorio]) {
                $ruta->cursos()->attach($cursoId, [
                    'numero_secuencia' => $secuencia,
                    'es_obligatorio' => $obligatorio,
                ]);
            }

            $ruta->duracion_total_horas = $ruta->calcularDuracionTotal();
            $ruta->save();
        }
    }
}
