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

// Test endpoint
if ($uri === '/test') {
    header('Content-Type: text/html; charset=utf-8');
    echo '<h1>Test Page</h1><p>PHP Server Working!</p><p>Time: ' . date('Y-m-d H:i:s') . '</p>';
    exit;
}

// Diagnostic endpoint
if ($uri === '/diagnostic') {
    chdir($appRoot);
    include $appRoot . '/diagnostic.php';
    exit;
}

$appRoot = __DIR__;

// For now, always serve the simple page to avoid 502 errors
if ($uri === '/' || $uri === '/index.php') {
    chdir($appRoot);
    include $appRoot . '/simple.php';
    exit;
}

// Serve static files if they exist
$path = realpath($appRoot . $uri);
if ($path && str_starts_with($path, $appRoot) && is_file($path)) {
    return false; // let the built-in server serve the file directly
}

// Default fallback - serve simple page
chdir($appRoot);
include $appRoot . '/simple.php';
