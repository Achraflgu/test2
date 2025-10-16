<?php
// Ultra-simple health check
header('Content-Type: application/json');
http_response_code(200);
echo '{"status":"ok","time":"' . date('Y-m-d H:i:s') . '"}';
?>
