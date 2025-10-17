<?php
// Root-level router for PHP built-in server
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

// Health endpoints
if ($uri === '/health' || $uri === '/health.php') {
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode(['status' => 'ok', 'time' => date('Y-m-d H:i:s')]);
    exit;
}

// Favicon: return 204 No Content
if ($uri === '/favicon.ico') {
    header('Content-Type: image/x-icon');
    http_response_code(204);
    exit;
}

$appRoot = __DIR__ . '/MSPORT';

// Force root to simple fallback to eliminate 502s
if ($uri === '/') {
    chdir($appRoot);
    require $appRoot . '/simple.php';
    exit;
}

// Serve static files under MSPORT if they exist
$path = realpath($appRoot . $uri);
if ($path && str_starts_with($path, $appRoot) && is_file($path)) {
    return false; // let the built-in server serve the file directly
}

// Fallback to the main application index
chdir($appRoot);
require $appRoot . '/index.php';
