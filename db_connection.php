<?php
// Production-ready database connection with environment variables

function parseDatabaseUrl(string $dbUrl): array {
    $url = parse_url($dbUrl);
    $query = parse_url($dbUrl, PHP_URL_QUERY) ?: '';
    parse_str($query, $params);

    return [
        'host' => $url['host'] ?? 'localhost',
        'port' => $url['port'] ?? 5432,
        'dbname' => ltrim($url['path'] ?? '', '/'),
        'user' => $url['user'] ?? 'postgres',
        'password' => $url['pass'] ?? '',
        'params' => $params,
        'type' => 'postgresql',
    ];
}

function buildPgDsn(array $cfg): string {
    $sslmode = $cfg['params']['sslmode'] ?? 'require';
    $connectTimeout = $cfg['params']['connect_timeout'] ?? 5; // seconds

    $parts = [
        'host=' . $cfg['host'],
        'port=' . $cfg['port'],
        'dbname=' . $cfg['dbname'],
        'sslmode=' . $sslmode,
        'connect_timeout=' . $connectTimeout,
    ];

    return 'pgsql:' . implode(';', $parts);
}

function getDatabaseConfig(): array {
    if (getenv('RAILWAY_ENVIRONMENT') || getenv('DATABASE_URL')) {
        $dbUrl = getenv('DATABASE_URL');
        if ($dbUrl) {
            return parseDatabaseUrl($dbUrl);
        }
        // Fallback to individual environment variables
        return [
            'host' => getenv('DB_HOST') ?: 'localhost',
            'port' => getenv('DB_PORT') ?: 5432,
            'dbname' => getenv('DB_NAME') ?: 'msport',
            'user' => getenv('DB_USER') ?: 'postgres',
            'password' => getenv('DB_PASSWORD') ?: '',
            'params' => [ 'sslmode' => 'require', 'connect_timeout' => 5 ],
            'type' => 'postgresql',
        ];
    }

    // Local development configuration (MySQL)
    return [
        'host' => 'localhost',
        'port' => 3306,
        'dbname' => 'msport',
        'user' => 'root',
        'password' => '',
        'type' => 'mysql',
    ];
}

function getConnection() {
    $config = getDatabaseConfig();

    if (($config['type'] ?? '') === 'postgresql') {
        $dsn = buildPgDsn($config);
        try {
            $pdo = new PDO($dsn, $config['user'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => false,
            ]);
            return $pdo;
        } catch (PDOException $e) {
            throw new Exception('PostgreSQL connection failed: ' . $e->getMessage());
        }
    }

    // MySQL connection (for local dev)
    $conn = @mysqli_connect($config['host'], $config['user'], $config['password'], $config['dbname']);
    if (!$conn) {
        throw new Exception('MySQL connection failed: ' . mysqli_connect_error());
    }
    return $conn;
}

// Get the appropriate connection
try {
    $conn = getConnection();
} catch (Exception $e) {
    // Let callers decide how to handle; expose a flag
    $conn = null;
    define('DB_CONNECTION_ERROR', $e->getMessage());
}

$config = getDatabaseConfig();

// Define constants for backward compatibility
if (!defined('SERVEUR')) {
    define('SERVEUR', $config['host'] ?? '');
}
if (!defined('BASE')) {
    define('BASE', $config['dbname'] ?? '');
}
if (!defined('USER')) {
    define('USER', $config['user'] ?? '');
}
if (!defined('MDP')) {
    define('MDP', $config['password'] ?? '');
}
if (!defined('DB_TYPE')) {
    define('DB_TYPE', $config['type'] ?? '');
}
?>
