<?php
// Ultra-simple index.php for Railway
header('Content-Type: text/html');
?>
<!DOCTYPE html>
<html>
<head>
    <title>MSport E-commerce</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f0f0f0; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .status { background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏪 MSport E-commerce Platform</h1>
        <div class="status">
            <strong>✅ Application Status:</strong> Running successfully on Railway
        </div>
        <p><strong>Server Time:</strong> <?php echo date('Y-m-d H:i:s T'); ?></p>
        <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
        <p><strong>Environment:</strong> <?php echo getenv('RAILWAY_ENVIRONMENT') ? 'Production' : 'Development'; ?></p>
        <hr>
        <h3>Available Endpoints:</h3>
        <ul>
            <li><a href="/health.php">Health Check</a></li>
            <li><a href="/app.php">Full Application</a></li>
        </ul>
    </div>
</body>
</html>