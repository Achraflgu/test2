<?php
// Diagnostic script to help debug deployment issues
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>MSport Diagnostic</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .status { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .ok { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 MSport Diagnostic Report</h1>
        
        <div class="status ok">
            <strong>✅ Server Status:</strong> PHP <?php echo phpversion(); ?> is running
        </div>
        
        <div class="status info">
            <strong>📅 Current Time:</strong> <?php echo date('Y-m-d H:i:s T'); ?>
        </div>
        
        <div class="status info">
            <strong>🌍 Environment:</strong> 
            <?php 
            echo getenv('RAILWAY_ENVIRONMENT') ? 'Production (Railway)' : 'Development';
            if (getenv('PORT')) echo ' | Port: ' . getenv('PORT');
            ?>
        </div>
        
        <div class="status <?php echo getenv('DATABASE_URL') ? 'ok' : 'error'; ?>">
            <strong>🗄️ Database:</strong> 
            <?php echo getenv('DATABASE_URL') ? 'URL configured' : 'No DATABASE_URL set'; ?>
        </div>
        
        <h3>📁 File System Check</h3>
        <pre><?php
        $files = ['router.php', 'simple.php', 'index.php', 'header.php', 'nav.php'];
        foreach ($files as $file) {
            $exists = file_exists($file);
            $readable = $exists ? is_readable($file) : false;
            echo sprintf("%-15s: %s %s\n", 
                $file, 
                $exists ? '✅ EXISTS' : '❌ MISSING',
                $readable ? '(readable)' : ($exists ? '(not readable)' : '')
            );
        }
        ?></pre>
        
        <h3>🔗 Request Information</h3>
        <pre><?php
        echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "\n";
        echo "REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'Not set') . "\n";
        echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'Not set') . "\n";
        echo "SERVER_NAME: " . ($_SERVER['SERVER_NAME'] ?? 'Not set') . "\n";
        echo "SERVER_PORT: " . ($_SERVER['SERVER_PORT'] ?? 'Not set') . "\n";
        ?></pre>
        
        <h3>🧪 Quick Tests</h3>
        <p><a href="/health" target="_blank">Test Health Endpoint</a></p>
        <p><a href="/test" target="_blank">Test Simple Endpoint</a></p>
        <p><a href="/favicon.ico" target="_blank">Test Favicon</a></p>
        
        <div class="status info">
            <strong>💡 Next Steps:</strong> If you're still getting 502 errors, check the deployment logs for PHP errors or missing dependencies.
        </div>
    </div>
</body>
</html>
