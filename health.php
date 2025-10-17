<?php
// Ultra-simple healthcheck endpoint
header('Content-Type: application/json');
http_response_code(200);
echo '{"status":"ok","timestamp":"' . time() . '"}';
exit;