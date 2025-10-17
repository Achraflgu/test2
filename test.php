<?php
// Simple test file to verify PHP server is working
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Page</title>
</head>
<body>
    <h1>PHP Server Test</h1>
    <p>Current time: <?php echo date('Y-m-d H:i:s'); ?></p>
    <p>PHP Version: <?php echo phpversion(); ?></p>
    <p>Request URI: <?php echo $_SERVER['REQUEST_URI'] ?? 'Not set'; ?></p>
    <p>Server working: ✅</p>
</body>
</html>
