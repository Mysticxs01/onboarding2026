<?php

namespace Database\Seeders;

use App\Models\Curso;
use App\Models\Area;
use Illuminate\Database\Seeder;

class CursoSeeder extends Seeder
{
    public function run(): void
    {
        $areaFormacion = Area::where('nombre', 'Formación y Capacitación')->first() ?? Area::first();

        $cursos = [
            // CURSOS DE SINERGIA FINANCIERA - 31 CURSOS
            
            // 1. INDUCCIÓN
            ['codigo' => 'CUR-001', 'nombre' => 'Inducción a la Cultura Cooperativa', 'objetivo' => 'Conocer la historia, valores y principios del modelo solidario.', 'modalidad' => 'Presencial', 'categoria' => 'Obligatorio', 'duracion_horas' => 4, 'costo' => 50],
            
            // 2. PRODUCTOS Y SERVICIOS
            ['codigo' => 'CUR-002', 'nombre' => 'Portafolio de Productos y Servicios', 'objetivo' => 'Dominar las características de los ahorros y créditos vigentes.', 'modalidad' => 'Virtual', 'categoria' => 'Obligatorio', 'duracion_horas' => 8, 'costo' => 60],
            
            // 3. NORMATIVA - SARLAFT
            ['codigo' => 'CUR-003', 'nombre' => 'Prevención de Lavado de Activos (SARLAFT)', 'objetivo' => 'Detectar operaciones sospechosas y cumplir con la ley financiera.', 'modalidad' => 'Virtual', 'categoria' => 'Cumplimiento Normativo', 'duracion_horas' => 4, 'costo' => 75],
            
            // 4. SEGURIDAD Y SALUD
            ['codigo' => 'CUR-004', 'nombre' => 'Seguridad y Salud en el Trabajo (SST)', 'objetivo' => 'Identificar riesgos laborales y protocolos de emergencia.', 'modalidad' => 'Presencial', 'categoria' => 'Cumplimiento Normativo', 'duracion_horas' => 4, 'costo' => 40],
            
            // 5. PRIMEROS AUXILIOS
            ['codigo' => 'CUR-005', 'nombre' => 'Brigadas de Primeros Auxilios', 'objetivo' => 'Capacitar al personal en respuesta ante accidentes físicos.', 'modalidad' => 'Presencial', 'categoria' => 'Cumplimiento Normativo', 'duracion_horas' => 6, 'costo' => 45],
            
            // 6. ACOSO LABORAL
            ['codigo' => 'CUR-006', 'nombre' => 'Prevención de Acoso Laboral', 'objetivo' => 'Fomentar un ambiente de respeto y convivencia sana.', 'modalidad' => 'Virtual', 'categoria' => 'Cumplimiento Normativo', 'duracion_horas' => 3, 'costo' => 35],
            
            // 7. EXTINTORES Y EVACUACIÓN
            ['codigo' => 'CUR-007', 'nombre' => 'Manejo de Extintores y Evacuación', 'objetivo' => 'Actuar correctamente ante incendios o desastres naturales.', 'modalidad' => 'Presencial', 'categoria' => 'Cumplimiento Normativo', 'duracion_horas' => 3, 'costo' => 30],
            
            // 8. VENTA CONSULTIVA
            ['codigo' => 'CUR-008', 'nombre' => 'Venta Consultiva Financiera', 'objetivo' => 'Desarrollar técnicas para ofrecer créditos según la necesidad del socio.', 'modalidad' => 'Presencial', 'categoria' => 'Desarrollo', 'duracion_horas' => 8, 'costo' => 70],
            
            // 9. TÉCNICAS DE NEGOCIACIÓN
            ['codigo' => 'CUR-009', 'nombre' => 'Técnicas de Negociación y Cierre', 'objetivo' => 'Mejorar la efectividad en la colocación de servicios financieros.', 'modalidad' => 'Virtual', 'categoria' => 'Desarrollo', 'duracion_horas' => 6, 'costo' => 65],
            
            // 10. CAPTACIÓN DE DEPÓSITOS
            ['codigo' => 'CUR-010', 'nombre' => 'Captación de Depósitos y Ahorro', 'objetivo' => 'Aprender estrategias para atraer liquidez a la cooperativa.', 'modalidad' => 'Virtual', 'categoria' => 'Desarrollo', 'duracion_horas' => 6, 'costo' => 60],
            
            // 11. ANÁLISIS DE CAPACIDAD DE PAGO
            ['codigo' => 'CUR-011', 'nombre' => 'Análisis de Capacidad de Pago', 'objetivo' => 'Estudiar detalladamente la relación ingreso/gasto del solicitante.', 'modalidad' => 'Presencial', 'categoria' => 'Desarrollo', 'duracion_horas' => 8, 'costo' => 80],
            
            // 12. INTERPRETACIÓN DE CENTRALES DE RIESGO
            ['codigo' => 'CUR-012', 'nombre' => 'Interpretación de Centrales de Riesgo', 'objetivo' => 'Aprender a leer e interpretar reportes de burós de crédito.', 'modalidad' => 'Virtual', 'categoria' => 'Desarrollo', 'duracion_horas' => 6, 'costo' => 70],
            
            // 13. EVALUACIÓN DE GARANTÍAS
            ['codigo' => 'CUR-013', 'nombre' => 'Evaluación de Garantías Reales', 'objetivo' => 'Conocer los aspectos legales de hipotecas y prendas vehiculares.', 'modalidad' => 'Presencial', 'categoria' => 'Desarrollo', 'duracion_horas' => 8, 'costo' => 85],
            
            // 14. GESTIÓN DE RIESGO OPERATIVO
            ['codigo' => 'CUR-014', 'nombre' => 'Gestión de Riesgo Operativo', 'objetivo' => 'Identificar fallas en procesos que puedan generar pérdidas.', 'modalidad' => 'Virtual', 'categoria' => 'Cumplimiento Normativo', 'duracion_horas' => 6, 'costo' => 75],
            
            // 15. GESTIÓN DE COBRANZA
            ['codigo' => 'CUR-015', 'nombre' => 'Gestión de Cobranza Preventiva', 'objetivo' => 'Aprender a contactar al socio antes de que caiga en mora.', 'modalidad' => 'Virtual', 'categoria' => 'Desarrollo', 'duracion_horas' => 5, 'costo' => 60],
            
            // 16. NORMAS NIIF
            ['codigo' => 'CUR-016', 'nombre' => 'Actualización en Normas NIIF', 'objetivo' => 'Aplicar los estándares internacionales de contabilidad vigentes.', 'modalidad' => 'Virtual', 'categoria' => 'Cumplimiento Normativo', 'duracion_horas' => 8, 'costo' => 95],
            
            // 17. FLUJO DE CAJA Y LIQUIDEZ
            ['codigo' => 'CUR-017', 'nombre' => 'Manejo de Flujo de Caja y Liquidez', 'objetivo' => 'Optimizar el dinero disponible para desembolsos diarios.', 'modalidad' => 'Presencial', 'categoria' => 'Desarrollo', 'duracion_horas' => 6, 'costo' => 70],
            
            // 18. REPORTES A ENTES DE CONTROL
            ['codigo' => 'CUR-018', 'nombre' => 'Preparación de Reportes a Entes de Control', 'objetivo' => 'Cumplir con los informes para la Superintendencia.', 'modalidad' => 'Virtual', 'categoria' => 'Cumplimiento Normativo', 'duracion_horas' => 6, 'costo' => 80],
            
            // 19. CIBERSEGURIDAD
            ['codigo' => 'CUR-019', 'nombre' => 'Ciberseguridad para No Técnicos', 'objetivo' => 'Enseñar al personal a evitar phishing y proteger contraseñas.', 'modalidad' => 'Virtual', 'categoria' => 'Cumplimiento Normativo', 'duracion_horas' => 4, 'costo' => 50],
            
            // 20. CORE FINANCIERO
            ['codigo' => 'CUR-020', 'nombre' => 'Manejo del Core Financiero (Software)', 'objetivo' => 'Capacitar en el uso de la plataforma principal de la entidad.', 'modalidad' => 'Presencial', 'categoria' => 'Desarrollo', 'duracion_horas' => 16, 'costo' => 120],
            
            // 21. PROTECCIÓN DE DATOS
            ['codigo' => 'CUR-021', 'nombre' => 'Protección de Datos Personales', 'objetivo' => 'Cumplir con la ley de Habeas Data y privacidad del socio.', 'modalidad' => 'Virtual', 'categoria' => 'Cumplimiento Normativo', 'duracion_horas' => 4, 'costo' => 40],
            
            // 22. LIDERAZGO Y TRABAJO EN EQUIPO
            ['codigo' => 'CUR-022', 'nombre' => 'Liderazgo y Trabajo en Equipo', 'objetivo' => 'Desarrollar habilidades blandas para coordinadores y jefes.', 'modalidad' => 'Presencial', 'categoria' => 'Desarrollo', 'duracion_horas' => 12, 'costo' => 100],
            
            // 23. INTELIGENCIA EMOCIONAL
            ['codigo' => 'CUR-023', 'nombre' => 'Inteligencia Emocional en el Trabajo', 'objetivo' => 'Brindar herramientas para el manejo del estrés y la empatía.', 'modalidad' => 'Virtual', 'categoria' => 'Desarrollo', 'duracion_horas' => 8, 'costo' => 80],
            
            // 24. COMUNICACIÓN ASERTIVA
            ['codigo' => 'CUR-024', 'nombre' => 'Comunicación Asertiva', 'objetivo' => 'Mejorar el flujo de información interna y externa.', 'modalidad' => 'Virtual', 'categoria' => 'Desarrollo', 'duracion_horas' => 6, 'costo' => 60],
            
            // 25. EXCEL AVANZADO
            ['codigo' => 'CUR-025', 'nombre' => 'Excel Avanzado para Finanzas', 'objetivo' => 'Dominar tablas dinámicas y fórmulas para análisis de datos.', 'modalidad' => 'Virtual', 'categoria' => 'Desarrollo', 'duracion_horas' => 10, 'costo' => 90],
            
            // 26. PROTOCOLO DE SERVICIO AL CLIENTE
            ['codigo' => 'CUR-026', 'nombre' => 'Protocolo de Servicio al Cliente', 'objetivo' => 'Estandarizar el saludo y la atención en las sucursales.', 'modalidad' => 'Presencial', 'categoria' => 'Desarrollo', 'duracion_horas' => 4, 'costo' => 50],
            
            // 27. MANEJO DE CLIENTES DIFÍCILES
            ['codigo' => 'CUR-027', 'nombre' => 'Manejo de Clientes Difíciles', 'objetivo' => 'Aprender técnicas de desescalamiento de conflictos.', 'modalidad' => 'Presencial', 'categoria' => 'Desarrollo', 'duracion_horas' => 6, 'costo' => 65],
            
            // 28. GESTIÓN DE PQRS
            ['codigo' => 'CUR-028', 'nombre' => 'Gestión de PQRS Eficiente', 'objetivo' => 'Reducir los tiempos de respuesta a los reclamos de los socios.', 'modalidad' => 'Virtual', 'categoria' => 'Desarrollo', 'duracion_horas' => 5, 'costo' => 55],
            
            // 29. MANIPULACIÓN DE PRODUCTOS QUÍMICOS
            ['codigo' => 'CUR-029', 'nombre' => 'Manipulación de Productos Químicos', 'objetivo' => 'Uso seguro de implementos de aseo (Para Servicios Generales).', 'modalidad' => 'Presencial', 'categoria' => 'Cumplimiento Normativo', 'duracion_horas' => 3, 'costo' => 30],
            
            // 30. MANTENIMIENTO PREVENTIVO DE SEDES
            ['codigo' => 'CUR-030', 'nombre' => 'Mantenimiento Preventivo de Sedes', 'objetivo' => 'Protocolos de revisión técnica locativa (Para Mantenimiento).', 'modalidad' => 'Presencial', 'categoria' => 'Cumplimiento Normativo', 'duracion_horas' => 6, 'costo' => 50],
            
            // 31. GESTIÓN DE COMPRAS Y PROVEEDORES
            ['codigo' => 'CUR-031', 'nombre' => 'Gestión de Compras y Proveedores', 'objetivo' => 'Aprender procesos de licitación y selección de compras.', 'modalidad' => 'Virtual', 'categoria' => 'Desarrollo', 'duracion_horas' => 6, 'costo' => 70],
        ];

        foreach ($cursos as $curso) {
            Curso::create([
                'codigo' => $curso['codigo'],
                'nombre' => $curso['nombre'],
                'descripcion' => $curso['objetivo'],
                'categoria' => $curso['categoria'],
                'modalidad' => $curso['modalidad'],
                'duracion_horas' => $curso['duracion_horas'],
                'objetivo' => $curso['objetivo'],
                'contenido' => $curso['nombre'],
                'area_responsable_id' => $areaFormacion->id,
                'costo' => $curso['costo'],
                'requiere_certificado' => true,
                'vigencia_meses' => 12,
                'activo' => true,
            ]);
        }
    }
}
