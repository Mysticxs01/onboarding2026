<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px 20px;
        }
        .alert {
            background-color: #e8f4f8;
            border-left: 4px solid #0284c7;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-title {
            font-weight: 600;
            color: #0c4a6e;
            margin-bottom: 5px;
        }
        .alert-text {
            color: #0c4a6e;
            font-size: 14px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 500;
            color: #6b7280;
            width: 40%;
        }
        .info-value {
            color: #1f2937;
            font-weight: 600;
            width: 60%;
            text-align: right;
        }
        .solicitudes-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .solicitudes-table thead {
            background-color: #f9fafb;
        }
        .solicitudes-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            font-size: 13px;
        }
        .solicitudes-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-in-progress {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .cta-button {
            display: inline-block;
            background-color: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: 600;
            text-align: center;
        }
        .cta-button:hover {
            background-color: #5568d3;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
        }
        .urgent {
            background-color: #fee2e2;
            border-left-color: #dc2626;
        }
        .urgent-text {
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📋 Nueva Solicitud de Ingreso</h1>
            <p>Proceso {{ $proceso->codigo }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Alert -->
            <div class="alert">
                <div class="alert-title">⚠️ Acción Requerida</div>
                <div class="alert-text">
                    Se ha creado un nuevo proceso de ingreso. Por favor, revisa las solicitudes y completa las gestiones requeridas antes de la fecha límite.
                </div>
            </div>

            <!-- Información del Empleado -->
            <div class="section">
                <div class="section-title">Información del Nuevo Empleado</div>
                <div class="info-row">
                    <div class="info-label">Código de Proceso:</div>
                    <div class="info-value">{{ $proceso->codigo }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nombre Completo:</div>
                    <div class="info-value">{{ $proceso->nombre_completo }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Cargo:</div>
                    <div class="info-value">{{ $proceso->cargo->nombre ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Área:</div>
                    <div class="info-value">{{ $nombreArea }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Fecha de Ingreso:</div>
                    <div class="info-value">{{ $proceso->fecha_ingreso->format('d/m/Y') }}</div>
                </div>
            </div>

            <!-- Solicitudes por Completar -->
            <div class="section">
                <div class="section-title">Solicitudes Asignadas a {{ $nombreArea }}</div>
                <table class="solicitudes-table">
                    <thead>
                        <tr>
                            <th>Tipo de Solicitud</th>
                            <th>Fecha Límite</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($solicitudes as $solicitud)
                        <tr>
                            <td>{{ $solicitud['tipo'] }}</td>
                            <td>{{ $solicitud['fechaLimite'] }}</td>
                            <td>
                                <span class="badge badge-pending">{{ $solicitud['estado'] }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Acceso al Panel -->
            <div class="section" style="text-align: center;">
                <p style="color: #6b7280; margin-bottom: 15px; font-size: 14px;">
                    Accede al panel de control para gestionar estas solicitudes:
                </p>
                <a href="{{ $urlPanel }}" class="cta-button">Ir al Panel de Solicitudes</a>
            </div>

            <!-- Notas Importantes -->
            <div class="section">
                <div class="section-title">⏰ Importante</div>
                <ul style="margin-left: 20px; font-size: 13px; color: #4b5563;">
                    <li style="margin-bottom: 8px;">
                        <strong>Fecha Límite:</strong> Las solicitudes deben completarse antes de la fecha de ingreso del empleado.
                    </li>
                    <li style="margin-bottom: 8px;">
                        <strong>Responsabilidad:</strong> Cada área es responsable de completar sus solicitudes a tiempo.
                    </li>
                    <li style="margin-bottom: 8px;">
                        <strong>Documentación:</strong> Asegúrate de que toda la documentación esté completa y verificada.
                    </li>
                    <li>
                        <strong>Contacto:</strong> Si tienes dudas, contacta al equipo de Recursos Humanos.
                    </li>
                </ul>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Este es un correo automático del Sistema de Onboarding - No responder a este correo</p>
            <p>{{ $fechaActual }}</p>
        </div>
    </div>
</body>
</html>
