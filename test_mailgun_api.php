<?php

// ⚠️ IMPORTANTE: Usar variables de entorno para credenciales
$apiKey = env('MAILGUN_SECRET', 'your-mailgun-api-key');
$domain = env('MAILGUN_DOMAIN', 'your-mailgun-domain');
$baseUrl = 'https://api.mailgun.net';

echo "🔍 Prueba directa con API de Mailgun\n";
echo str_repeat("=", 80) . "\n\n";

// Endpoint para enviar email
$url = $baseUrl . "/v3/{$domain}/messages";

// Datos del email
$postData = [
    'from' => 'postmaster@' . $domain,
    'to' => 'sinergianotificaciones0@gmail.com',
    'subject' => '✅ Prueba de Email - HU15 Notificaciones',
    'text' => 'Este es un email de prueba de Mailgun para validar la configuración de HU15.',
    'html' => '<html><body><h1>✅ Prueba Exitosa</h1><p>Este email fue enviado desde Mailgun usando la API directamente.</p><p><strong>Sistema:</strong> Onboarding Sinergia</p></body></html>'
];

// Crear contexto para el request
$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => [
            'Authorization: Basic ' . base64_encode('api:' . $apiKey),
            'Content-Type: application/x-www-form-urlencoded',
        ],
        'content' => http_build_query($postData),
        'timeout' => 30,
    ]
]);

echo "📧 Enviando email...\n";
echo "FROM: postmaster@{$domain}\n";
echo "TO: sinergianotificaciones0@gmail.com\n";
echo "SUBJECT: ✅ Prueba de Email - HU15 Notificaciones\n";
echo "\n";

try {
    $response = file_get_contents($url, false, $context);
    $httpCode = $http_response_header[0] ?? 'Unknown';
    
    echo "✅ RESPUESTA DEL SERVIDOR:\n";
    echo $httpCode . "\n";
    echo $response . "\n\n";
    
    if (strpos($httpCode, '200') !== false) {
        echo "🎉 ¡EMAIL ENVIADO EXITOSAMENTE!\n";
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

?>
