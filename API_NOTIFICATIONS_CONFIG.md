# Guía de Integración de APIs - Módulo de Notificaciones

## Overview

El sistema de onboarding incluye un módulo de notificaciones que puede integrarse con servicios externos para:

- **Enviar automáticamente cambios de estado de solicitudes**
- **Notificar a empleados nuevos sobre credenciales**
- **Informar de entregas de equipos y uniformes**

## Configuración Básica

### 1. Variables de Entorno (.env)

Agrega las siguientes variables a tu archivo `.env`:

```env
# Proveedor de notificaciones: custom, sendgrid, mailgun
NOTIFICATIONS_PROVIDER=custom
NOTIFICATIONS_API_KEY=tu-clave-api-aquí
NOTIFICATIONS_API_URL=https://tu-api.ejemplo.com/v1

# Si usas SendGrid
SENDGRID_API_KEY=tu-clave-sendgrid-aquí
```

## Proveedores Soportados

### Opción 1: API Personalizada

Para usar una API personalizada debes proporcionar:

**Endpoint para Cambios de Estado:**
- **URL:** `POST {NOTIFICATIONS_API_URL}/notifications/send`
- **Headers:** 
  - `Authorization: Bearer {NOTIFICATIONS_API_KEY}`
  - `Content-Type: application/json`

**Body esperado:**
```json
{
  "destinatario": "nombre.empleado@empresa.com",
  "asunto": "Actualización de Solicitud: Tecnología",
  "mensaje": "Tu solicitud de Tecnología cambió de 'Pendiente' a 'En Proceso'",
  "tipo": "estado_solicitud",
  "referencia": 123,
  "area": "Tecnología e Información"
}
```

**Endpoint para Credenciales:**
- **URL:** `POST {NOTIFICATIONS_API_URL}/credentials/send`
- **Body esperado:**
```json
{
  "email": "nuevo.empleado@empresa.com",
  "nombre": "Nuevo Empleado",
  "usuario": "nuevo.empleado",
  "contraseña_temporal": "contraseña-temporal-aquí",
  "empresa": "Nombre de Empresa",
  "fecha_inicio": "2026-02-08"
}
```

### Opción 2: SendGrid

1. Crea una cuenta en [SendGrid](https://sendgrid.com)
2. Obtén tu API Key desde el panel de control
3. Agrega la clave a `.env`:
```env
SENDGRID_API_KEY=SG.xxxxxxxxxxxxxxxxxxxxxxxx
NOTIFICATIONS_PROVIDER=sendgrid
```

### Opción 3: Sin API (Modo Demo)

Si no configuras una API, el sistema:
- Registrará los cambios en los logs (`storage/logs/`)
- Las notificaciones se registrarán pero no se enviarán

## Uso en el Sistema

### Cambio de Estado de Solicitudes

Cuando un operador marca una solicitud como "En Proceso" o "Entregado", automáticamente:

1. Se registra el cambio de estado en la base de datos
2. Se envía una notificación a la API configurada
3. Se registra en los logs del sistema

**Errores manejados:**
- Si la API no está disponible, la solicitud se actualiza igualmente
- Se registra un warning en los logs
- El usuario ve el cambio pero no la notificación fallida

### Ciclo de Estados

```
Pendiente → En Proceso → Entregado → Completado
   ↑                                      ↑
   └──────── (Operador inicia) ──────────┘
```

## Ejemplos de Implementación

### Ejemplo 1: Endpoint Personalizado en Node.js/Express

```javascript
const express = require('express');
const app = express();

app.post('/v1/notifications/send', (req, res) => {
  const { destinatario, asunto, mensaje, tipo, referencia, area } = req.body;
  
  console.log(`[${tipo}] Notificación para ${destinatario}`);
  console.log(`Área: ${area}`);
  console.log(`Mensaje: ${mensaje}`);
  
  // Aquí enviar email, SMS, o notificación push
  sendEmail(destinatario, asunto, mensaje);
  
  res.status(200).json({ success: true, message_id: referencia });
});

app.post('/v1/credentials/send', (req, res) => {
  const { email, nombre, usuario, contraseña_temporal } = req.body;
  
  // Enviar credenciales por email
  sendCredentials(email, { nombre, usuario, contraseña_temporal });
  
  res.status(200).json({ success: true });
});

app.listen(3000);
```

### Ejemplo 2: API REST Genérica (curl para pruebas)

```bash
# Cambio de estado
curl -X POST https://tu-api.ejemplo.com/v1/notifications/send \
  -H "Authorization: Bearer tu-clave-api" \
  -H "Content-Type: application/json" \
  -d '{
    "destinatario": "empleado@empresa.com",
    "asunto": "Actualización de Solicitud",
    "mensaje": "Tu solicitud cambió a Entregado",
    "tipo": "estado_solicitud",
    "referencia": 123,
    "area": "Tecnología"
  }'

# Enviar credenciales
curl -X POST https://tu-api.ejemplo.com/v1/credentials/send \
  -H "Authorization: Bearer tu-clave-api" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "nuevo@empresa.com",
    "nombre": "Nuevo Usuario",
    "usuario": "nuevo.usuario",
    "contraseña_temporal": "Temporal123!",
    "empresa": "Mi Empresa",
    "fecha_inicio": "2026-02-08"
  }'
```

## Monitoreo y Debugging

### Ver Logs de Notificaciones

```bash
# Ver últimas notificaciones
tail -f storage/logs/laravel.log | grep "notificacion\|cambio_estado"

# Buscar errores
grep "notificacion_error" storage/logs/laravel.log
```

### Verificar PDF Logs

Los cambios de estado se registran en:
- **Ubicación:** `/storage/logs/laravel.log`
- **Evento:** `cambio_estado_solicitud`
- **Detalles:** ID, estado anterior/nuevo, usuario, timestamp

## Mejores Prácticas

1. **Reintentos:** La API implementa timeout de 10-15 segundos. Para fallos, se recomienda:
   - Implementar reintentos exponenciales en tu API
   - Guardar notificaciones pendientes en cola

2. **Seguridad:**
   - Usa HTTPS para todas las conexiones
   - Valida el API Key en cada request
   - Ciphera datos sensibles (contraseñas, información de empleado)

3. **Escalabilidad:**
   - Procesa notificaciones en background workers/queues
   - Implementa rate limiting para evitar sobrecarga

4. **Auditoría:**
   - Registra todas las notificaciones enviadas
   - Guarda historial de errores
   - Mantén logs de acceso a APIs

## Troubleshooting

### Notificaciones no se envían
1. Verifica `.env` tiene `NOTIFICATIONS_API_URL` correcto
2. Comprueba que la API está disponible: `curl {NOTIFICATIONS_API_URL}/health`
3. Revisa logs: `tail -f storage/logs/laravel.log`

### Error "API_KEY no configurado"
- Asegúrate de que `NOTIFICATIONS_API_KEY` está en `.env`
- Ejecuta: `php artisan config:cache`

### Timeout en conexión
- Aumenta timeout en `app/Services/NotificationService.php` (línea ~70)
- Verifica que tu API responde: `curl -v {API_URL}`

## Soporte

Para más información sobre integrtación:
- Email: soporte@empresa.com
- Documentación del API: Ver archivo `API_INTEGRATION.md`
