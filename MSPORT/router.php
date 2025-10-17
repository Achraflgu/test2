<?php
// Router for PHP built-in server
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

// Health endpoints
if ($uri === '/health' || $uri === '/health.php') {
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode(['status' => 'ok', 'time' => date('Y-m-d H:i:s')]);
    exit;
}

// Favicon: return 204 No Content to avoid 502s
if ($uri === '/favicon.ico') {
    header('Content-Type: image/x-icon');
    http_response_code(204);
    exit;
}

// Serve existing static files normally
$path = __DIR__ . $uri;
if (php_sapi_name() === 'cli-server' && is_file($path)) {
    return false; // let server serve the file
}

// Fallback to the main index
require __DIR__ . '/index.php';
