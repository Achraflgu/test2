<?php
// Absolute minimal router with error handling
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    
    // Root path - absolute minimal response
    if ($uri === '/' || $uri === '/index.php') {
        header('Content-Type: text/html');
        echo '<h1>MSport Working</h1>';
        exit;
    }
    
    // Health check
    if ($uri === '/health') {
        header('Content-Type: application/json');
        echo '{"status":"ok"}';
        exit;
    }
    
    // Favicon
    if ($uri === '/favicon.ico') {
        http_response_code(204);
        exit;
    }
    
    // Default
    header('Content-Type: text/html');
    echo '<h1>MSport Default</h1>';
    exit;
    
} catch (Exception $e) {
    header('Content-Type: text/html');
    echo '<h1>Error: ' . htmlspecialchars($e->getMessage()) . '</h1>';
    exit;
}
