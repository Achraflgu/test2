<?php
// Simple health check endpoint for Railway
// This endpoint doesn't depend on database connection

header('Content-Type: application/json');
http_response_code(200);

$response = [
    'status' => 'healthy',
    'timestamp' => date('Y-m-d H:i:s'),
    'service' => 'MSport E-commerce',
    'version' => '1.0.0',
    'environment' => getenv('RAILWAY_ENVIRONMENT') ? 'production' : 'development'
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>
