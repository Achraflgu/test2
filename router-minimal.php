<?php
// Ultra-minimal router to isolate 502 issues
$uri = $_SERVER['REQUEST_URI'] ?? '/';

// Immediate response for root path - no includes, no dependencies
if ($uri === '/' || $uri === '/index.php') {
    header('Content-Type: text/html; charset=utf-8');
    http_response_code(200);
    echo '<!DOCTYPE html><html><head><title>MSport</title></head><body><h1>MSport E-commerce</h1><p>Server is working!</p><p>Time: ' . date('Y-m-d H:i:s') . '</p></body></html>';
    exit;
}

// Health check
if ($uri === '/health') {
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode(['status' => 'ok', 'time' => date('Y-m-d H:i:s')]);
    exit;
}

// Favicon
if ($uri === '/favicon.ico') {
    http_response_code(204);
    exit;
}

// Default fallback
header('Content-Type: text/html; charset=utf-8');
http_response_code(200);
echo '<h1>MSport</h1><p>Default response</p>';
exit;
