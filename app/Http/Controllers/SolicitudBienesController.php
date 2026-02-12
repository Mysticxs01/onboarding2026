<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\ItemInmobiliario;
use Illuminate\Http\Request;

class SolicitudBienesController extends Controller
{
    /**
     * Mostrar formulario de asignación de inmobiliario
     */
    public function mostrarFormulario(Solicitud $solicitud)
    {
        $this->authorize('view', $solicitud);

        $items = $solicitud->itemsInmobiliario;

        return view('areas.bienes', compact('solicitud', 'items'));
    }

    /**
     * Guardar asignación de inmobiliario
     */
    public function guardar(Request $request, Solicitud $solicitud)
    {
        $this->authorize('update', $solicitud);

        $validated = $request->validate([
            'items.*.nombre' => 'required|string',
            'items.*.cantidad' => 'required|numeric|min:1',
            'items.*.estado' => 'required|in:Pendiente,En Almacén,En Tránsito,Entregado',
            'papeleria' => 'nullable|array',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        // Guardar items de inmobiliario
        if (isset($validated['items'])) {
            foreach ($validated['items'] as $item) {
                ItemInmobiliario::create([
                    'solicitud_id' => $solicitud->id,
                    'tipo_item' => $this->determinarTipo($item['nombre']),
                    'descripcion' => $item['nombre'],
                    'cantidad' => (int) $item['cantidad'],
                    'estado' => $item['estado'],
                    'observaciones' => $validated['observaciones'] ?? null,
                ]);
            }
        }

        // Guardar papelería
        if (isset($request->papeleria)) {
            foreach ($request->papeleria as $articulo) {
                ItemInmobiliario::create([
                    'solicitud_id' => $solicitud->id,
                    'tipo_item' => 'Papelería',
                    'descripcion' => $articulo,
                    'cantidad' => 1,
                    'estado' => 'Pendiente',
                ]);
            }
        }

        return back()->with('success', '✓ Asignación de inmobiliario guardada correctamente.');
    }

    /**
     * Determinar tipo de item basado en su nombre
     */
    private function determinarTipo(string $nombre): string
    {
        $nombre = strtolower($nombre);

        if (str_contains($nombre, 'silla')) return 'Silla';
        if (str_contains($nombre, 'escritorio')) return 'Escritorio';
        if (str_contains($nombre, 'estantería')) return 'Estantería';
        if (str_contains($nombre, 'lámpara')) return 'Lámpara';
        if (str_contains($nombre, 'papel')) return 'Papelería';

        return 'Otro';
    }

    /**
     * Cargar kit estándar para un cargo
     */
    public function cargarKitEstandar(Solicitud $solicitud)
    {
        $this->authorize('view', $solicitud);

        $kit = ItemInmobiliario::obtenerKitEstandarPorCargo($solicitud->proceso->cargo_id);

        return response()->json([
            'success' => true,
            'kit' => $kit,
            'mensaje' => "Kit estándar cargado para {$solicitud->proceso->cargo->nombre}",
        ]);
    }

    /**
     * Actualizar estado de item
     */
    public function actualizarEstadoItem(Request $request, ItemInmobiliario $item)
    {
        $this->authorize('update', $item->solicitud);

        $validated = $request->validate([
            'estado' => 'required|in:Pendiente,En Almacén,En Tránsito,Entregado',
        ]);

        $item->update($validated);

        $estados = [
            'Pendiente' => '⏰',
            'En Almacén' => '📦',
            'En Tránsito' => '🚚',
            'Entregado' => '✅',
        ];

        return back()->with('success', "{$estados[$validated['estado']]} {$item->descripcion} - Estado actualizado a {$validated['estado']}");
    }

    /**
     * Marcar item como entregado
     */
    public function marcarEntregado(Request $request, ItemInmobiliario $item)
    {
        $this->authorize('update', $item->solicitud);

        $validated = $request->validate([
            'entregado_por' => 'required|string',
        ]);

        $item->update([
            'estado' => 'Entregado',
            'entregado_por' => $validated['entregado_por'],
            'fecha_entrega' => now(),
        ]);

        return back()->with('success', "✓ {$item->descripcion} entregado por {$validated['entregado_por']}");
    }

    /**
     * Ver resumen de asignación
     */
    public function verResumen(Solicitud $solicitud)
    {
        $this->authorize('view', $solicitud);

        $items = $solicitud->itemsInmobiliario;

        if ($items->isEmpty()) {
            return back()->withErrors(['items' => 'No hay items asignados.']);
        }

        $itemsPorEstado = $items->groupBy('estado');

        return view('areas.bienes-resumen', compact('solicitud', 'items', 'itemsPorEstado'));
    }

    /**
     * Generar reporte de entrega
     */
    public function generarReporte(Solicitud $solicitud)
    {
        $this->authorize('view', $solicitud);

        $items = $solicitud->itemsInmobiliario;
        $entregados = $items->where('estado', 'Entregado')->count();
        $total = $items->count();

        $reporte = [
            'empleado' => $solicitud->proceso->nombre_completo,
            'cargo' => $solicitud->proceso->cargo->nombre,
            'fecha' => now()->format('d/m/Y H:i'),
            'total_items' => $total,
            'entregados' => $entregados,
            'pendientes' => $total - $entregados,
            'porcentaje_entrega' => $total > 0 ? round(($entregados / $total) * 100, 2) : 0,
            'items' => $items->toArray(),
        ];

        return response()->json($reporte);
    }
}
