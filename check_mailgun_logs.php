<?php

// ⚠️ IMPORTANTE: Usar variables de entorno para credenciales
$apiKey = env('MAILGUN_SECRET', 'your-mailgun-api-key');
$domain = env('MAILGUN_DOMAIN', 'your-mailgun-domain');
$baseUrl = 'https://api.mailgun.net';

echo "🔍 Verificando logs de Mailgun\n";
echo str_repeat("=", 80) . "\n\n";

// Endpoint para ver logs
$url = $baseUrl . "/v3/{$domain}/events?limit=25&ascending=no";

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => [
            'Authorization: Basic ' . base64_encode('api:' . $apiKey),
        ],
        'timeout' => 30,
    ]
]);

try {
    $response = file_get_contents($url, false, $context);
    $data = json_decode($response, true);
    
    if ($data && isset($data['items'])) {
        echo "📧 Últimos eventos en Mailgun:\n\n";
        
        foreach ($data['items'] as $event) {
            echo "Evento: " . strtoupper($event['event']) . "\n";
            echo "Destinatario: " . ($event['recipient'] ?? 'N/A') . "\n";
            echo "Asunto: " . ($event['message']['headers']['subject'] ?? 'N/A') . "\n";
            echo "Timestamp: " . $event['timestamp'] . "\n";
            
            if ($event['event'] === 'failed') {
                echo "❌ Razón del fallo: " . json_encode($event['delivery-status'] ?? $event['reason'] ?? 'Unknown') . "\n";
            }
            
            if (isset($event['flags'])) {
                echo "Flags: " . json_encode($event['flags']) . "\n";
            }
            
            echo "\n";
        }
    } else {
        echo "Error: " . print_r($data, true);
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

?>
