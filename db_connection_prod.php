<?php
// Production database connection with environment variables
function getDatabaseConfig() {
    // Check if we're in production (Railway)
    if (getenv('RAILWAY_ENVIRONMENT') || getenv('DATABASE_URL')) {
        // Production configuration using environment variables
        $dbUrl = getenv('DATABASE_URL');
        if ($dbUrl) {
            $url = parse_url($dbUrl);
            return [
                'host' => $url['host'],
                'port' => $url['port'] ?? 5432,
                'dbname' => ltrim($url['path'], '/'),
                'user' => $url['user'],
                'password' => $url['pass'],
                'type' => 'postgresql'
            ];
        }
        
        // Fallback to individual environment variables
        return [
            'host' => getenv('DB_HOST') ?: 'localhost',
            'port' => getenv('DB_PORT') ?: 5432,
            'dbname' => getenv('DB_NAME') ?: 'msport',
            'user' => getenv('DB_USER') ?: 'postgres',
            'password' => getenv('DB_PASSWORD') ?: '',
            'type' => 'postgresql'
        ];
    } else {
        // Local development configuration
        return [
            'host' => 'localhost',
            'port' => 3306,
            'dbname' => 'msport',
            'user' => 'root',
            'password' => '',
            'type' => 'mysql'
        ];
    }
}

function getConnection() {
    $config = getDatabaseConfig();
    
    if ($config['type'] === 'postgresql') {
        // PostgreSQL connection
        $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
        try {
            $pdo = new PDO($dsn, $config['user'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return $pdo;
        } catch (PDOException $e) {
            die("PostgreSQL Connection failed: " . $e->getMessage());
        }
    } else {
        // MySQL connection (for backward compatibility)
        $conn = mysqli_connect($config['host'], $config['user'], $config['password'], $config['dbname']);
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
    define("SERVEUR", $config['host']);
}
if (!defined("BASE")) {
    define("BASE", $config['dbname']);
}
if (!defined("USER")) {
    define("USER", $config['user']);
}
if (!defined("MDP")) {
    define("MDP", $config['password']);
}
if (!defined("DB_TYPE")) {
    define("DB_TYPE", $config['type']);
}
?>

