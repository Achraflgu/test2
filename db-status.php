<?php
// Database setup and test script for MSport
header('Content-Type: text/html; charset=utf-8');

try {
    include 'db_connection.php';
    
    echo '<h1>MSport Database Status</h1>';
    echo '<p><strong>Database Type:</strong> ' . (defined('DB_TYPE') ? DB_TYPE : 'Not defined') . '</p>';
    echo '<p><strong>Connection Status:</strong> ';
    
    if (defined('DB_TYPE') && DB_TYPE === 'postgresql') {
        $test_query = $conn->query("SELECT 1");
        if ($test_query) {
            echo '✅ Connected successfully</p>';
            echo '<p><strong>Database URL:</strong> ' . (getenv('DATABASE_URL') ? 'Configured' : 'Not set') . '</p>';
            
            // Test basic queries
            try {
                $result = $conn->query("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = 'public'");
                $row = $result->fetch(PDO::FETCH_ASSOC);
                echo '<p><strong>Tables in database:</strong> ' . $row['count'] . '</p>';
                
                if ($row['count'] > 0) {
                    echo '<h2>Database Tables:</h2><ul>';
                    $tables = $conn->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
                    while ($table = $tables->fetch(PDO::FETCH_ASSOC)) {
                        echo '<li>' . $table['table_name'] . '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p><strong>⚠️ No tables found. You may need to run the database setup.</strong></p>';
                    echo '<p><a href="setup_neon_database.php">Run Database Setup</a></p>';
                }
            } catch (Exception $e) {
                echo '<p><strong>⚠️ Database query failed:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
        } else {
            echo '❌ Connection failed</p>';
        }
    } else {
        if ($conn && !mysqli_connect_error()) {
            echo '✅ MySQL Connected successfully</p>';
        } else {
            echo '❌ MySQL Connection failed</p>';
        }
    }
    
} catch (Exception $e) {
    echo '<h1>Database Error</h1>';
    echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><strong>Environment:</strong> ' . (getenv('RAILWAY_ENVIRONMENT') ? 'Production' : 'Development') . '</p>';
    echo '<p><strong>DATABASE_URL:</strong> ' . (getenv('DATABASE_URL') ? 'Set' : 'Not set') . '</p>';
}
?>
