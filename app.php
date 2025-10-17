<?php
// Single-file PHP application for Railway deployment
// This eliminates router complexity and potential issues

// Set error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the request URI
$uri = $_SERVER['REQUEST_URI'] ?? '/';

// Handle different routes
switch ($uri) {
    case '/':
    case '/index.php':
        header('Content-Type: text/html');
        echo '<!DOCTYPE html>
<html>
<head>
    <title>MSport E-commerce</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f0f0f0; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .status { background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .info { background: #d1ecf1; padding: 10px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏪 MSport E-commerce Platform</h1>
        <div class="status">
            <strong>✅ Application Status:</strong> Running successfully on Railway
        </div>
        <div class="info">
            <p><strong>Server Time:</strong> ' . date('Y-m-d H:i:s T') . '</p>
            <p><strong>PHP Version:</strong> ' . phpversion() . '</p>
            <p><strong>Environment:</strong> ' . (getenv('RAILWAY_ENVIRONMENT') ? 'Production' : 'Development') . '</p>
            <p><strong>Port:</strong> ' . (getenv('PORT') ?: '3000 (hardcoded)') . '</p>
            <p><strong>Server Port:</strong> ' . ($_SERVER['SERVER_PORT'] ?? 'Not set') . '</p>
            <p><strong>Host:</strong> ' . ($_SERVER['HTTP_HOST'] ?? 'Not set') . '</p>
        </div>
        <hr>
        <h3>Available Endpoints:</h3>
        <ul>
            <li><a href="/health">Health Check</a></li>
            <li><a href="/test">Test Page</a></li>
        </ul>
    </div>
</body>
</html>';
        break;
        
    case '/health':
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'ok',
            'time' => date('Y-m-d H:i:s'),
            'php_version' => phpversion(),
            'environment' => getenv('RAILWAY_ENVIRONMENT') ?: 'development',
            'port' => getenv('PORT') ?: '3000',
            'server_port' => $_SERVER['SERVER_PORT'] ?? 'not_set'
        ]);
        break;
        
    case '/test':
        header('Content-Type: text/html');
        echo '<h1>Test Page</h1><p>PHP is working correctly!</p><p>Time: ' . date('Y-m-d H:i:s') . '</p>';
        break;
        
    case '/favicon.ico':
        http_response_code(204);
        break;
        
    default:
        header('Content-Type: text/html');
        echo '<h1>MSport</h1><p>Page not found: ' . htmlspecialchars($uri) . '</p>';
        break;
}

exit;
