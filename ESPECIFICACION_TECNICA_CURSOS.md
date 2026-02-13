# 📊 ESPECIFICACIÓN TÉCNICA DETALLADA - MÓDULO DE CURSOS
## Implementación Paso a Paso

**Documento:** Especificación Técnica  
**Modulo:** Gestión de Cursos y Asignación de Formación  
**Versión:** 1.0  
**Fecha:** Febrero 13, 2026

---

## TABLA DE CONTENIDOS

1. [Migraciones de Base de Datos](#1-migraciones-de-base-de-datos)
2. [Modelos Eloquent](#2-modelos-eloquent)
3. [Controladores](#3-controladores)
4. [Vistas Blade](#4-vistas-blade)
5. [Rutas](#5-rutas)
6. [Servicios de Dominio](#6-servicios-de-dominio)
7. [Tests](#7-tests)
8. [Seeders](#8-seeders)

---

## 1. MIGRACIONES DE BASE DE DATOS

### 1.1 Migración: Crear tabla `cursos`

**Archivo:** `database/migrations/2026_02_20_000001_create_cursos_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            
            // Identificación
            $table->string('codigo')->unique();  // CURSO-001, CURSO-002, etc.
            $table->string('nombre', 255);
            $table->text('descripcion')->nullable();
            
            // Categorización
            $table->enum('categoria', [
                'Obligatorio',
                'Opcional',
                'Cumplimiento Normativo',
                'Desarrollo',
                'Liderazgo'
            ])->default('Opcional');
            
            $table->enum('modalidad', [
                'Presencial',
                'Virtual',
                'Híbrida'
            ])->default('Virtual');
            
            // Contenido
            $table->integer('duracion_horas');
            $table->text('objetivo')->nullable();
            $table->longText('contenido')->nullable();
            
            // Relaciones
            $table->foreignId('area_responsable_id')
                  ->nullable()
                  ->constrained('areas')
                  ->onDelete('set null');
            
            // Administración
            $table->decimal('costo', 10, 2)->default(0);
            $table->boolean('requiere_certificado')->default(true);
            $table->integer('vigencia_meses')->nullable();  // NULL = sin vencimiento
            
            // Estados
            $table->boolean('activo')->default(true);
            $table->softDeletes();
            
            $table->timestamps();
            
            // Índices
            $table->index('codigo');
            $table->index('categoria');
            $table->index(['activo', 'categoria']);
            $table->index('area_responsable_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
```

### 1.2 Migración: Crear tabla `curso_x_cargo`

**Archivo:** `database/migrations/2026_02_20_000002_create_curso_x_cargo_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curso_x_cargo', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('curso_id')
                  ->constrained('cursos')
                  ->onDelete('cascade');
            
            $table->foreignId('cargo_id')
                  ->constrained('cargos')
                  ->onDelete('cascade');
            
            // Configuración
            $table->boolean('es_obligatorio')->default(false);
            $table->integer('orden_secuencia')->default(0);
            
            // Vigencia de la asignación
            $table->date('fecha_desde')->nullable();
            $table->date('fecha_hasta')->nullable();
            
            $table->timestamps();
            
            // Índices y constraints
            $table->unique(['curso_id', 'cargo_id']);
            $table->index(['cargo_id', 'es_obligatorio']);
            $table->index('fecha_desde');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curso_x_cargo');
    }
};
```

### 1.3 Migración: Crear tabla `curso_x_area`

**Archivo:** `database/migrations/2026_02_20_000003_create_curso_x_area_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curso_x_area', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('curso_id')
                  ->constrained('cursos')
                  ->onDelete('cascade');
            
            $table->foreignId('area_id')
                  ->constrained('areas')
                  ->onDelete('cascade');
            
            $table->boolean('es_obligatorio')->default(false);
            
            $table->timestamps();
            
            // Índices y constraints
            $table->unique(['curso_id', 'area_id']);
            $table->index(['area_id', 'es_obligatorio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curso_x_area');
    }
};
```

### 1.4 Migración: Crear tabla `asignacion_cursos`

**Archivo:** `database/migrations/2026_02_20_000004_create_asignacion_cursos_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asignacion_cursos', function (Blueprint $table) {
            $table->id();
            
            // Relaciones principales
            $table->foreignId('proceso_ingreso_id')
                  ->constrained('procesos_ingresos')
                  ->onDelete('cascade');
            
            $table->foreignId('curso_id')
                  ->constrained('cursos')
                  ->onDelete('cascade');
            
            // Fechas
            $table->date('fecha_asignacion');
            $table->date('fecha_limite')->nullable();
            $table->date('fecha_completacion')->nullable();
            
            // Estado del curso
            $table->enum('estado', [
                'Asignado',
                'En Progreso',
                'Completado',
                'Vencido',
                'Cancelado'
            ])->default('Asignado');
            
            // Evaluación
            $table->integer('calificacion')->nullable();  // 0-100
            $table->string('certificado_url')->nullable();
            
            // Auditoría
            $table->foreignId('asignado_por_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            
            $table->foreignId('responsable_validacion_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            
            $table->text('observaciones')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index('proceso_ingreso_id');
            $table->index('curso_id');
            $table->index('estado');
            $table->index(['fecha_asignacion', 'estado']);
            $table->unique(['proceso_ingreso_id', 'curso_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignacion_cursos');
    }
};
```

### 1.5 Migración: Crear tabla `rutas_formacion`

**Archivo:** `database/migrations/2026_02_20_000005_create_rutas_formacion_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rutas_formacion', function (Blueprint $table) {
            $table->id();
            
            // Identificación
            $table->string('nombre', 255);
            $table->text('descripcion')->nullable();
            
            // Relaciones
            $table->foreignId('cargo_id')
                  ->nullable()
                  ->constrained('cargos')
                  ->onDelete('set null');
            
            $table->foreignId('area_id')
                  ->nullable()
                  ->constrained('areas')
                  ->onDelete('set null');
            
            // Control de versiones
            $table->string('version')->default('1.0');
            $table->boolean('activa')->default(true);
            
            // Información
            $table->integer('duracion_total_horas')->default(0);
            $table->date('fecha_vigencia')->nullable();
            
            // Responsable
            $table->foreignId('responsable_rrhh_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            
            $table->softDeletes();
            $table->timestamps();
            
            // Índices
            $table->index(['cargo_id', 'activa']);
            $table->index(['area_id', 'activa']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rutas_formacion');
    }
};
```

### 1.6 Migración: Crear tabla `ruta_x_curso`

**Archivo:** `database/migrations/2026_02_20_000006_create_ruta_x_curso_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ruta_x_curso', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('ruta_id')
                  ->constrained('rutas_formacion')
                  ->onDelete('cascade');
            
            $table->foreignId('curso_id')
                  ->constrained('cursos')
                  ->onDelete('cascade');
            
            // Configuración
            $table->integer('numero_secuencia')->default(0);
            $table->boolean('es_obligatorio')->default(true);
            $table->boolean('es_requisito_previo')->default(false);
            
            $table->timestamps();
            
            // Índices
            $table->unique(['ruta_id', 'curso_id']);
            $table->index(['ruta_id', 'numero_secuencia']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ruta_x_curso');
    }
};
```

### 1.7 Migración: Crear tabla `auditoria_onboarding`

**Archivo:** `database/migrations/2026_02_20_000007_create_auditoria_onboarding_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditoria_onboarding', function (Blueprint $table) {
            $table->id();
            
            // Quién
            $table->foreignId('usuario_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            
            // Qué
            $table->enum('accion', [
                'create',
                'update',
                'delete',
                'view',
                'export',
                'anular'
            ]);
            
            // Dónde
            $table->string('entidad');  // Nombre de tabla
            $table->unsignedBigInteger('entidad_id');
            
            // Cómo - valores antes y después
            $table->json('valores_anteriores')->nullable();
            $table->json('valores_nuevos')->nullable();
            
            // Por qué
            $table->text('motivo')->nullable();
            
            // Contexto técnico
            $table->string('ip_origin')->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index('usuario_id');
            $table->index(['entidad', 'entidad_id']);
            $table->index('accion');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria_onboarding');
    }
};
```

### 1.8 Migración: Agregar campos a `procesos_ingresos`

**Archivo:** `database/migrations/2026_02_20_000008_add_fields_to_procesos_ingresos_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('procesos_ingresos', function (Blueprint $table) {
            // Campos faltantes
            $table->string('email')->nullable()->after('nombre_completo');
            $table->string('telefono')->nullable()->after('email');
            $table->text('observaciones')->nullable()->change();  // Aumentar a text
            $table->date('fecha_esperada_finalizacion')
                  ->nullable()
                  ->after('fecha_ingreso');
        });
    }

    public function down(): void
    {
        Schema::table('procesos_ingresos', function (Blueprint $table) {
            $table->dropColumn(['email', 'telefono', 'fecha_esperada_finalizacion']);
        });
    }
};
```

---

## 2. MODELOS ELOQUENT

### 2.1 Modelo: `Curso`

**Archivo:** `app/Models/Curso.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curso extends Model
{
    use SoftDeletes;

    protected $table = 'cursos';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'categoria',
        'modalidad',
        'duracion_horas',
        'objetivo',
        'contenido',
        'area_responsable_id',
        'costo',
        'requiere_certificado',
        'vigencia_meses',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'requiere_certificado' => 'boolean',
    ];

    /* ===========================
       RELACIONES
       =========================== */

    public function areaResponsable()
    {
        return $this->belongsTo(Area::class, 'area_responsable_id');
    }

    public function cargos()
    {
        return $this->belongsToMany(
            Cargo::class,
            'curso_x_cargo',
            'curso_id',
            'cargo_id'
        )
        ->withPivot('es_obligatorio', 'orden_secuencia', 'fecha_desde', 'fecha_hasta')
        ->withTimestamps();
    }

    public function areas()
    {
        return $this->belongsToMany(
            Area::class,
            'curso_x_area',
            'curso_id',
            'area_id'
        )
        ->withPivot('es_obligatorio')
        ->withTimestamps();
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionCurso::class, 'curso_id');
    }

    public function rutas()
    {
        return $this->belongsToMany(
            RutaFormacion::class,
            'ruta_x_curso',
            'curso_id',
            'ruta_id'
        )
        ->withPivot('numero_secuencia', 'es_obligatorio', 'es_requisito_previo')
        ->orderBy('numero_secuencia');
    }

    /* ===========================
       SCOPES
       =========================== */

    public function scopeActivos(Builder $query)
    {
        return $query->where('activo', true);
    }

    public function scopePorCategoria(Builder $query, string $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    public function scopePorModalidad(Builder $query, string $modalidad)
    {
        return $query->where('modalidad', $modalidad);
    }

    public function scopePorArea(Builder $query, int $areaId)
    {
        return $query->where('area_responsable_id', $areaId);
    }

    public function scopeObligatorios(Builder $query)
    {
        return $query->where('categoria', 'Obligatorio');
    }

    /* ===========================
       MÉTODOS HELPERS
       =========================== */

    public function obtenerCursosPorCargo(Cargo $cargo)
    {
        return $this->cargos()
                    ->where('cargo_id', $cargo->id)
                    ->get();
    }

    public function esObligatorioParaCargo(Cargo $cargo): bool
    {
        return $this->cargos()
                    ->where('cargo_id', $cargo->id)
                    ->wherePivot('es_obligatorio', true)
                    ->exists();
    }

    public function esVigente(): bool
    {
        if ($this->vigencia_meses === null) {
            return true;  // Sin vigencia
        }

        $mesesTranscurridos = $this->created_at->diffInMonths(now());
        return $mesesTranscurridos <= $this->vigencia_meses;
    }

    public function obtenerTotalAsignaciones(): int
    {
        return $this->asignaciones()->count();
    }

    public function obtenerTotalCompletados(): int
    {
        return $this->asignaciones()
                    ->where('estado', 'Completado')
                    ->count();
    }

    public function obtenerTasaCompletacion(): float
    {
        $total = $this->obtenerTotalAsignaciones();
        if ($total === 0) {
            return 0;
        }
        return ($this->obtenerTotalCompletados() / $total) * 100;
    }
}
```

### 2.2 Modelo: `AsignacionCurso`

**Archivo:** `app/Models/AsignacionCurso.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionCurso extends Model
{
    protected $table = 'asignacion_cursos';

    protected $fillable = [
        'proceso_ingreso_id',
        'curso_id',
        'fecha_asignacion',
        'fecha_limite',
        'fecha_completacion',
        'estado',
        'calificacion',
        'certificado_url',
        'asignado_por_id',
        'responsable_validacion_id',
        'observaciones',
    ];

    protected $casts = [
        'fecha_asignacion' => 'date',
        'fecha_limite' => 'date',
        'fecha_completacion' => 'date',
    ];

    /* ===========================
       RELACIONES
       =========================== */

    public function procesIngreso()
    {
        return $this->belongsTo(ProcesoIngreso::class, 'proceso_ingreso_id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function asignadoPor()
    {
        return $this->belongsTo(User::class, 'asignado_por_id');
    }

    public function responsableValidacion()
    {
        return $this->belongsTo(User::class, 'responsable_validacion_id');
    }

    /* ===========================
       SCOPES
       =========================== */

    public function scopeAsignados(Builder $query)
    {
        return $query->where('estado', 'Asignado');
    }

    public function scopeEnProgreso(Builder $query)
    {
        return $query->where('estado', 'En Progreso');
    }

    public function scopeCompletados(Builder $query)
    {
        return $query->where('estado', 'Completado');
    }

    public function scopeVencidos(Builder $query)
    {
        return $query->where('estado', 'Vencido');
    }

    public function scopePorProcesoIngreso(Builder $query, int $procesoId)
    {
        return $query->where('proceso_ingreso_id', $procesoId);
    }

    /* ===========================
       MÉTODOS HELPERS
       =========================== */

    public function estaVencido(): bool
    {
        if ($this->fecha_limite === null) {
            return false;
        }
        return now()->isAfter($this->fecha_limite) 
               && $this->estado !== 'Completado';
    }

    public function marcarEnProgreso(): void
    {
        $this->update([
            'estado' => 'En Progreso',
        ]);
    }

    public function marcarCompletado(int $calificacion = null): void
    {
        $this->update([
            'estado' => 'Completado',
            'fecha_completacion' => now()->toDateString(),
            'calificacion' => $calificacion,
        ]);
    }

    public function marcarVencido(): void
    {
        $this->update([
            'estado' => 'Vencido',
        ]);
    }

    public function obtenerEstadoColor(): string
    {
        return match($this->estado) {
            'Asignado' => 'blue',
            'En Progreso' => 'orange',
            'Completado' => 'green',
            'Vencido' => 'red',
            'Cancelado' => 'gray',
        };
    }

    public function obtenerEmoji(): string
    {
        return match($this->estado) {
            'Asignado' => '📋',
            'En Progreso' => '▶️',
            'Completado' => '✅',
            'Vencido' => '⏰',
            'Cancelado' => '❌',
        };
    }
}
```

### 2.3 Modelo: `RutaFormacion`

**Archivo:** `app/Models/RutaFormacion.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RutaFormacion extends Model
{
    use SoftDeletes;

    protected $table = 'rutas_formacion';

    protected $fillable = [
        'nombre',
        'descripcion',
        'cargo_id',
        'area_id',
        'version',
        'activa',
        'duracion_total_horas',
        'fecha_vigencia',
        'responsable_rrhh_id',
    ];

    protected $casts = [
        'activa' => 'boolean',
        'fecha_vigencia' => 'date',
    ];

    /* ===========================
       RELACIONES
       =========================== */

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function responsableRRHH()
    {
        return $this->belongsTo(User::class, 'responsable_rrhh_id');
    }

    public function cursos()
    {
        return $this->belongsToMany(
            Curso::class,
            'ruta_x_curso',
            'ruta_id',
            'curso_id'
        )
        ->withPivot('numero_secuencia', 'es_obligatorio', 'es_requisito_previo')
        ->orderBy('numero_secuencia');
    }

    /* ===========================
       MÉTODOS HELPERS
       =========================== */

    public function obtenerCursosOrdenados()
    {
        return $this->cursos()->get();
    }

    public function obtenerCursosObligatorios()
    {
        return $this->cursos()
                    ->wherePivot('es_obligatorio', true)
                    ->get();
    }

    public function obtenerDuracionTotal(): int
    {
        return $this->cursos()->sum('duracion_horas');
    }

    public function agregarCurso(Curso $curso, int $secuencia, bool $esObligatorio = true)
    {
        $this->cursos()->attach($curso->id, [
            'numero_secuencia' => $secuencia,
            'es_obligatorio' => $esObligatorio,
        ]);
    }

    public function removerCurso(Curso $curso)
    {
        $this->cursos()->detach($curso->id);
    }
}
```

---

## 3. CONTROLADORES

### 3.1 CursoController

**Archivo:** `app/Http/Controllers/Formacion/CursoController.php`

```php
<?php

namespace App\Http\Controllers\Formacion;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Area;
use App\Http\Requests\StoreCursoRequest;
use App\Http\Requests\UpdateCursoRequest;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
        $this->middleware('can:gestionar-cursos');
    }

    /**
     * Listado de cursos
     */
    public function index(Request $request)
    {
        $query = Curso::query();

        // Filtros
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }
        if ($request->filled('modalidad')) {
            $query->where('modalidad', $request->modalidad);
        }
        if ($request->filled('area')) {
            $query->where('area_responsable_id', $request->area);
        }
        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('codigo', 'like', "%{$request->buscar}%")
                  ->orWhere('nombre', 'like', "%{$request->buscar}%");
            });
        }

        // Si no es admin, solo ver activos
        if (!auth()->user()->hasRole('Admin')) {
            $query->where('activo', true);
        }

        $cursos = $query
            ->with('areaResponsable')
            ->latest()
            ->paginate(15);

        $areas = Area::orderBy('nombre')->get();
        $categorias = [
            'Obligatorio',
            'Opcional',
            'Cumplimiento Normativo',
            'Desarrollo',
            'Liderazgo'
        ];
        $modalidades = ['Presencial', 'Virtual', 'Híbrida'];

        return view('formacion.cursos.index', compact(
            'cursos', 'areas', 'categorias', 'modalidades'
        ));
    }

    /**
     * Formulario crear curso
     */
    public function create()
    {
        $this->authorize('create', Curso::class);

        $areas = Area::orderBy('nombre')->get();

        return view('formacion.cursos.create', compact('areas'));
    }

    /**
     * Guardar curso nuevo
     */
    public function store(StoreCursoRequest $request)
    {
        $this->authorize('create', Curso::class);

        // Generar código automático
        $ultimoCurso = Curso::max('id') ?? 0;
        $codigo = 'CURSO-' . str_pad($ultimoCurso + 1, 3, '0', STR_PAD_LEFT);

        $curso = Curso::create([
            'codigo' => $codigo,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'categoria' => $request->categoria,
            'modalidad' => $request->modalidad,
            'duracion_horas' => $request->duracion_horas,
            'objetivo' => $request->objetivo,
            'contenido' => $request->contenido,
            'area_responsable_id' => $request->area_responsable_id,
            'costo' => $request->costo ?? 0,
            'requiere_certificado' => $request->requiere_certificado,
            'vigencia_meses' => $request->vigencia_meses,
            'activo' => true,
        ]);

        // Registrar auditoría
        \Log::info("Curso creado: {$curso->codigo} por " . auth()->user()->name);

        return redirect()
            ->route('cursos.show', $curso)
            ->with('success', "✅ Curso '{$curso->nombre}' creado correctamente");
    }

    /**
     * Ver detalles del curso
     */
    public function show(Curso $curso)
    {
        $this->authorize('view', $curso);

        $estadisticas = [
            'total_asignaciones' => $curso->obtenerTotalAsignaciones(),
            'completados' => $curso->obtenerTotalCompletados(),
            'tasa_completacion' => $curso->obtenerTasaCompletacion(),
        ];

        return view('formacion.cursos.show', compact('curso', 'estadisticas'));
    }

    /**
     * Formulario editar
     */
    public function edit(Curso $curso)
    {
        $this->authorize('update', $curso);

        $areas = Area::orderBy('nombre')->get();

        return view('formacion.cursos.edit', compact('curso', 'areas'));
    }

    /**
     * Actualizar curso
     */
    public function update(UpdateCursoRequest $request, Curso $curso)
    {
        $this->authorize('update', $curso);

        $oldValues = $curso->toArray();

        $curso->update($request->validated());

        // Registrar auditoría
        \Log::info("Curso actualizado: {$curso->codigo}", [
            'cambios' => array_diff_assoc($curso->toArray(), $oldValues)
        ]);

        return redirect()
            ->route('cursos.show', $curso)
            ->with('success', '✅ Curso actualizado correctamente');
    }

    /**
     * Eliminar lógico (soft delete)
     */
    public function destroy(Curso $curso)
    {
        $this->authorize('delete', $curso);

        $curso->delete();

        \Log::warning("Curso eliminado: {$curso->codigo}");

        return redirect()
            ->route('cursos.index')
            ->with('success', '✅ Curso eliminado correctamente');
    }
}
```

### 3.2 AsignacionCursoController (RRHH)

**Archivo:** `app/Http/Controllers/Formacion/AsignacionCursoController.php`

```php
<?php

namespace App\Http\Controllers\Formacion;

use App\Http\Controllers\Controller;
use App\Models\ProcesoIngreso;
use App\Models\AsignacionCurso;
use App\Models\Curso;
use App\Services\FormacionService;
use Illuminate\Http\Request;

class AsignacionCursoController extends Controller
{
    protected FormacionService $formacionService;

    public function __construct(FormacionService $formacionService)
    {
        $this->formacionService = $formacionService;
        $this->middleware('auth');
        $this->middleware('can:asignar-cursos');
    }

    /**
     * Panel RRHH: Asignar cursos a nuevos empleados
     */
    public function index()
    {
        // Procesos pendientes de asignación de cursos
        $procesos = ProcesoIngreso::where('estado', '!=', 'Cancelado')
            ->with(['cargo', 'area'])
            ->latest()
            ->paginate(10);

        return view('formacion.asignaciones.index', compact('procesos'));
    }

    /**
     * Asignar cursos a un empleado
     */
    public function asignar(ProcesoIngreso $proceso)
    {
        // Obtener cursos sugeridos por IA
        $cursosSugeridos = $this->formacionService
            ->obtenerCursosSugeridos($proceso);

        // Obtener todos los cursos disponibles
        $cursosDisponibles = Curso::activos()
            ->get()
            ->groupBy('categoria');

        return view('formacion.asignaciones.asignar', compact(
            'proceso',
            'cursosSugeridos',
            'cursosDisponibles'
        ));
    }

    /**
     * Guardar asignaciones
     */
    public function guardar(Request $request, ProcesoIngreso $proceso)
    {
        $request->validate([
            'cursos' => 'required|array|min:1',
            'cursos.*' => 'required|exists:cursos,id',
            'fecha_inicio' => 'required|date|after_or_equal:today',
        ]);

        $cursosAsignados = [];

        foreach ($request->cursos as $cursoId) {
            $asignacion = AsignacionCurso::create([
                'proceso_ingreso_id' => $proceso->id,
                'curso_id' => $cursoId,
                'fecha_asignacion' => now()->toDateString(),
                'fecha_limite' => $request->fecha_inicio,
                'estado' => 'Asignado',
                'asignado_por_id' => auth()->id(),
            ]);

            $cursosAsignados[] = $asignacion->curso->nombre;
        }

        // Enviar notificación al empleado
        $this->formacionService->notificarAsignacionCursos(
            $proceso,
            $cursosAsignados
        );

        // Registrar auditoría
        \Log::info("Cursos asignados a {$proceso->nombre_completo}", [
            'cantidad' => count($cursosAsignados),
            'cursos' => $cursosAsignados
        ]);

        return redirect()
            ->route('procesos-ingreso.show', $proceso)
            ->with('success', '✅ Cursos asignados correctamente');
    }

    /**
     * Ver asignaciones de un empleado
     */
    public function verAsignaciones(ProcesoIngreso $proceso)
    {
        $asignaciones = $proceso->asignacionesCursos()
            ->with('curso')
            ->get();

        return view('formacion.asignaciones.ver', compact('proceso', 'asignaciones'));
    }

    /**
     * Marcar curso como completado
     */
    public function marcarCompletado(Request $request, AsignacionCurso $asignacion)
    {
        $request->validate([
            'calificacion' => 'required|integer|min:0|max:100',
        ]);

        $asignacion->marcarCompletado($request->calificacion);

        return back()->with('success', '✅ Curso marcado como completado');
    }
}
```

---

## 4. VISTAS BLADE

### 4.1 Vista: Listado de Cursos

**Archivo:** `resources/views/formacion/cursos/index.blade.php`

```blade
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">📚 Gestión de Cursos</h2>
            @can('crear-cursos')
                <a href="{{ route('cursos.create') }}" class="btn-primary">
                    ➕ Nuevo Curso
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4">

            {{-- Filtros --}}
            <div class="bg-white p-6 rounded shadow mb-6">
                <form method="GET" action="{{ route('cursos.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    
                    <div>
                        <input type="text" name="buscar" placeholder="Código o nombre..." 
                               value="{{ request('buscar') }}"
                               class="form-control-corporate w-full">
                    </div>

                    <div>
                        <select name="categoria" class="form-control-corporate w-full">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat }}" 
                                        {{ request('categoria') === $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="modalidad" class="form-control-corporate w-full">
                            <option value="">Todas las modalidades</option>
                            @foreach($modalidades as $mod)
                                <option value="{{ $mod }}"
                                        {{ request('modalidad') === $mod ? 'selected' : '' }}>
                                    {{ $mod }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="area" class="form-control-corporate w-full">
                            <option value="">Todas las áreas</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}"
                                        {{ request('area') == $area->id ? 'selected' : '' }}>
                                    {{ $area->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn-primary w-full">
                        🔍 Filtrar
                    </button>
                </form>
            </div>

            {{-- Tabla de cursos --}}
            <div class="bg-white rounded shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left">#</th>
                            <th class="px-6 py-3 text-left">Código</th>
                            <th class="px-6 py-3 text-left">Nombre</th>
                            <th class="px-6 py-3 text-left">Categoría</th>
                            <th class="px-6 py-3 text-left">Duración</th>
                            <th class="px-6 py-3 text-left">Estado</th>
                            <th class="px-6 py-3 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cursos as $curso)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3">{{ $corso->id }}</td>
                                <td class="px-6 py-3">
                                    <span class="badge badge-primary">{{ $curso->codigo }}</span>
                                </td>
                                <td class="px-6 py-3">{{ $curso->nombre }}</td>
                                <td class="px-6 py-3">
                                    <span class="px-3 py-1 rounded-full text-sm"
                                          style="background-color: {{ $this->getCategoryColor($curso->categoria) }}20;
                                                  color: {{ $this->getCategoryColor($curso->categoria) }};">
                                        {{ $curso->categoria }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">{{ $curso->duracion_horas }}h</td>
                                <td class="px-6 py-3">
                                    @if($curso->activo)
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    <a href="{{ route('cursos.show', $curso) }}" 
                                       class="text-blue-600 hover:text-blue-800 mr-3">Ver</a>
                                    @can('update', $curso)
                                        <a href="{{ route('cursos.edit', $curso) }}" 
                                           class="text-orange-600 hover:text-orange-800">Editar</a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    No hay cursos disponibles
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="mt-6">
                {{ $cursos->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
```

---

## 5. RUTAS

**Archivo:** `routes/web-formacion.php`

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Formacion\{
    CursoController,
    RutaFormacionController,
    AsignacionCursoController,
};

Route::middleware(['auth', 'verified'])->group(function () {

    // CURSOS
    Route::resource('cursos', CursoController::class);
    Route::get('/cursos/estadisticas/resumen', [CursoController::class, 'estadisticas'])
         ->name('cursos.estadisticas');

    // RUTAS DE FORMACIÓN
    Route::resource('rutas-formacion', RutaFormacionController::class);
    Route::post('/rutas-formacion/{ruta}/agregar-curso', 
                [RutaFormacionController::class, 'agregarCurso'])
         ->name('rutas-formacion.agregar-curso');

    // ASIGNACIONES (RRHH)
    Route::resource('asignaciones-cursos', AsignacionCursoController::class)
         ->middleware('can:asignar-cursos');
    
    Route::post('/asignaciones-cursos/proceso/{proceso}/guardar',
                [AsignacionCursoController::class, 'guardar'])
         ->name('asignaciones.guardar');

    Route::get('/asignaciones-cursos/proceso/{proceso}',
               [AsignacionCursoController::class, 'asignar'])
         ->name('asignaciones.asignar');

});
```

---

## 6. SERVICIOS DE DOMINIO

### 6.1 FormacionService

**Archivo:** `app/Services/FormacionService.php`

```php
<?php

namespace App\Services;

use App\Models\{ProcesoIngreso, Curso, AsignacionCurso};

class FormacionService
{
    /**
     * Obtener cursos sugeridos por IA
     */
    public function obtenerCursosSugeridos(ProcesoIngreso $proceso): array
    {
        $cargo = $proceso->cargo;
        $area = $proceso->area;

        // Cursos obligatorios por cargo
        $cursosObligatorios = Curso::whereHas('cargos', function($q) use ($cargo) {
            $q->where('cargo_id', $cargo->id)
              ->where('es_obligatorio', true);
        })->get();

        // Cursos obligatorios por área
        $cursosPorArea = Curso::whereHas('areas', function($q) use ($area) {
            $q->where('area_id', $area->id)
              ->where('es_obligatorio', true);
        })->get();

        return [
            'obligatorios' => $cursosObligatorios,
            'por_area' => $cursosPorArea,
        ];
    }

    /**
     * Notificar asignación de cursos
     */
    public function notificarAsignacionCursos(ProcesoIngreso $proceso, array $cursos): void
    {
        // Enviar email
        \Mail::send('emails.cursos-asignados', [
            'empleado' => $proceso->nombre_completo,
            'cursos' => $cursos,
        ], function ($m) use ($proceso) {
            $m->to($proceso->email)
              ->subject('Tus Cursos de Formación Corporativa');
        });

        // Crear notificación en BD (opcional)
        // ...
    }

    /**
     * Obtener progreso de formación
     */
    public function obtenerProgresoFormacion(ProcesoIngreso $proceso)
    {
        $asignaciones = $proceso->asignacionesCursos()
            ->with('curso')
            ->get();

        $total = $asignaciones->count();
        $completados = $asignaciones
            ->where('estado', 'Completado')
            ->count();

        return [
            'total' => $total,
            'completados' => $completados,
            'porcentaje' => $total > 0 ? ($completados / $total) * 100 : 0,
            'asignaciones' => $asignaciones,
        ];
    }
}
```

---

## 7. TESTS

### 7.1 CursoTest

**Archivo:** `tests/Feature/CursoTest.php`

```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\{Curso, User, Area};

class CursoTest extends RefreshDatabase
{
    protected User $admin;
    protected Area $area;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->area = Area::factory()->create();
        $this->admin = User::factory()
            ->create()
            ->assignRole('Admin');
    }

    public function test_puede_crear_curso()
    {
        $this->actingAs($this->admin);

        $response = $this->post('/cursos', [
            'codigo' => 'CURSO-001',
            'nombre' => 'SARLAFT Básico',
            'categoria' => 'Cumplimiento Normativo',
            'modalidad' => 'Virtual',
            'duracion_horas' => 8,
            'area_responsable_id' => $this->area->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cursos', [
            'nombre' => 'SARLAFT Básico'
        ]);
    }

    public function test_puede_listar_cursos()
    {
        $this->actingAs($this->admin);

        Curso::factory()->count(5)->create();

        $response = $this->get('/cursos');
        $response->assertStatus(200);
        $response->assertViewHas('cursos');
    }

    public function test_no_autenticado_no_puede_ver_cursos()
    {
        $response = $this->get('/cursos');
        $response->assertRedirect('/login');
    }
}
```

---

## 8. SEEDERS

### 8.1 CursoSeeder

**Archivo:** `database/seeders/CursoSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Curso, Area};

class CursoSeeder extends Seeder
{
    public function run(): void
    {
        $areaFormacion = Area::where('nombre', 'Formación')->first();

        $cursos = [
            [
                'codigo' => 'CURSO-001',
                'nombre' => 'Inducción Corporativa',
                'descripcion' => 'Bienvenida e inducción a la empresa',
                'categoria' => 'Obligatorio',
                'modalidad' => 'Presencial',
                'duracion_horas' => 8,
                'requiere_certificado' => true,
                'vigencia_meses' => null,
            ],
            [
                'codigo' => 'CURSO-002',
                'nombre' => 'SARLAFT Básico',
                'descripcion' => 'Cumplimiento normativo SARLAFT',
                'categoria' => 'Cumplimiento Normativo',
                'modalidad' => 'Virtual',
                'duracion_horas' => 4,
                'requiere_certificado' => true,
                'vigencia_meses' => 12,
            ],
            [
                'codigo' => 'CURSO-003',
                'nombre' => 'Seguridad y Salud en el Trabajo',
                'descripcion' => 'SST - Normativa y prevención',
                'categoria' => 'Cumplimiento Normativo',
                'modalidad' => 'Virtual',
                'duracion_horas' => 6,
                'requiere_certificado' => true,
                'vigencia_meses' => 12,
            ],
            // ... más cursos
        ];

        foreach ($cursos as $curso) {
            Curso::create(array_merge($curso, [
                'area_responsable_id' => $areaFormacion->id ?? null,
                'activo' => true,
            ]));
        }
    }
}
```

---

**Este documento es la base técnica para implementar el módulo de Cursos.**  
**Próximos pasos: Ejecutar migraciones → Crear modelos → Implementar controladores → Vistas → Tests**

