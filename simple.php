<?php
// Ultra-simple page that always works
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>MSport - Loading...</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #667eea; 
            color: white; 
            text-align: center; 
            padding: 50px; 
            margin: 0;
        }
        .container { 
            background: rgba(255,255,255,0.1); 
            padding: 40px; 
            border-radius: 20px; 
            max-width: 500px; 
            margin: 0 auto;
        }
        .spinner {
            border: 4px solid rgba(255,255,255,0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏪 MSport E-commerce</h1>
        <p>Setting up database connection...</p>
        <div class="spinner"></div>
        <p>Status: <?php echo getenv('DATABASE_URL') ? 'Database URL configured' : 'Database URL not set'; ?></p>
        <p>Environment: <?php echo getenv('RAILWAY_ENVIRONMENT') ? 'Production' : 'Development'; ?></p>
        <p><small>This page will refresh automatically</small></p>
    </div>
    
    <script>
        setTimeout(function() {
            window.location.reload();
        }, 5000);
    </script>
</body>
</html>
