<?php
/**
 * Script to update database connections for production deployment
 * This script will update your existing db_connection.php files to use the new production connection
 */

function updateDbConnectionFile($filePath) {
    if (!file_exists($filePath)) {
        echo "File not found: $filePath\n";
        return false;
    }
    
    $content = file_get_contents($filePath);
    
    // Check if already updated
    if (strpos($content, 'getenv') !== false || strpos($content, 'DATABASE_URL') !== false) {
        echo "File already updated: $filePath\n";
        return true;
    }
    
    // Create backup
    $backupPath = $filePath . '.backup.' . date('Y-m-d-H-i-s');
    file_put_contents($backupPath, $content);
    echo "Backup created: $backupPath\n";
    
    // New content with environment variable support
    $newContent = '<?php
// Production-ready database connection
function getDatabaseConfig() {
    // Check if we\'re in production (Railway)
    if (getenv(\'RAILWAY_ENVIRONMENT\') || getenv(\'DATABASE_URL\')) {
        // Production configuration using environment variables
        $dbUrl = getenv(\'DATABASE_URL\');
        if ($dbUrl) {
            $url = parse_url($dbUrl);
            return [
                \'host\' => $url[\'host\'],
                \'port\' => $url[\'port\'] ?? 5432,
                \'dbname\' => ltrim($url[\'path\'], \'/\'),
                \'user\' => $url[\'user\'],
                \'password\' => $url[\'pass\'],
                \'type\' => \'postgresql\'
            ];
        }
        
        // Fallback to individual environment variables
        return [
            \'host\' => getenv(\'DB_HOST\') ?: \'localhost\',
            \'port\' => getenv(\'DB_PORT\') ?: 5432,
            \'dbname\' => getenv(\'DB_NAME\') ?: \'msport\',
            \'user\' => getenv(\'DB_USER\') ?: \'postgres\',
            \'password\' => getenv(\'DB_PASSWORD\') ?: \'\',
            \'type\' => \'postgresql\'
        ];
    } else {
        // Local development configuration
        return [
            \'host\' => \'localhost\',
            \'port\' => 3306,
            \'dbname\' => \'msport\',
            \'user\' => \'root\',
            \'password\' => \'\',
            \'type\' => \'mysql\'
        ];
    }
}

function getConnection() {
    $config = getDatabaseConfig();
    
    if ($config[\'type\'] === \'postgresql\') {
        // PostgreSQL connection
        $dsn = "pgsql:host={$config[\'host\']};port={$config[\'port\']};dbname={$config[\'dbname\']}";
        try {
            $pdo = new PDO($dsn, $config[\'user\'], $config[\'password\'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return $pdo;
        } catch (PDOException $e) {
            die("PostgreSQL Connection failed: " . $e->getMessage());
        }
    } else {
        // MySQL connection (for backward compatibility)
        $conn = mysqli_connect($config[\'host\'], $config[\'user\'], $config[\'password\'], $config[\'dbname\']);
        if (!$conn) {
            die("MySQL Connection failed: " . mysqli_connect_error());
        }
        return $conn;
    }
}

// Get the appropriate connection
$conn = getConnection();
$config = getDatabaseConfig();

// Define constants for backward compatibility
if (!defined("SERVEUR")) {
    define("SERVEUR", $config[\'host\']);
}
if (!defined("BASE")) {
    define("BASE", $config[\'dbname\']);
}
if (!defined("USER")) {
    define("USER", $config[\'user\']);
}
if (!defined("MDP")) {
    define("MDP", $config[\'password\']);
}
if (!defined("DB_TYPE")) {
    define("DB_TYPE", $config[\'type\']);
}
?>';
    
    // Write new content
    if (file_put_contents($filePath, $newContent)) {
        echo "Successfully updated: $filePath\n";
        return true;
    } else {
        echo "Failed to update: $filePath\n";
        return false;
    }
}

// Main execution
if (php_sapi_name() === 'cli') {
    echo "Updating database connection files for production deployment...\n\n";
    
    $filesToUpdate = [
        'db_connection.php',
        'admin/db_connection.php'
    ];
    
    foreach ($filesToUpdate as $file) {
        echo "Processing: $file\n";
        updateDbConnectionFile($file);
        echo "\n";
    }
    
    echo "Update completed!\n";
    echo "Next steps:\n";
    echo "1. Test your local application to ensure it still works\n";
    echo "2. Deploy to Railway with the environment variables set\n";
    echo "3. Import your database to Neon PostgreSQL\n";
} else {
    echo "This script should be run from command line.\n";
}
?>

