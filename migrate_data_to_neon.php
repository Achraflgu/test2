<?php
/**
 * Data Migration Script: MySQL to Neon PostgreSQL
 * This script helps migrate your existing MySQL data to Neon PostgreSQL
 */

// Configuration - Update these with your actual database details
$mysql_config = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'msport'
];

// Neon PostgreSQL configuration - Update with your Neon details
$postgres_config = [
    'host' => 'your-neon-host.neon.tech',
    'port' => '5432',
    'database' => 'your-neon-database',
    'username' => 'your-neon-username',
    'password' => 'your-neon-password'
];

// Tables to migrate (in order to respect foreign key constraints)
$tables_to_migrate = [
    'admins',
    'brands',
    'productcategories',
    'products',
    'customers',
    'coupons',
    'blog',
    'blogreviews',
    'contact',
    'delivery_methods',
    'payment_methods',
    'settings',
    'orders',
    'orderitems',
    'order_details',
    'payments',
    'productreviews',
    'product_coupons',
    'shipping_info',
    'cart',
    'shoppingcart',
    'wishlist'
];

// Data type conversion mapping
$type_conversions = [
    'int(11)' => 'integer',
    'varchar(255)' => 'varchar(255)',
    'varchar(100)' => 'varchar(100)',
    'varchar(20)' => 'varchar(20)',
    'varchar(50)' => 'varchar(50)',
    'text' => 'text',
    'longtext' => 'text',
    'datetime' => 'timestamp',
    'date' => 'date',
    'time' => 'time',
    'decimal(10,2)' => 'decimal(10,2)',
    'float' => 'real',
    'double' => 'double precision',
    'tinyint(1)' => 'boolean',
    'timestamp' => 'timestamp'
];

function connectMySQL($config) {
    $conn = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);
    if ($conn->connect_error) {
        die("MySQL Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function connectPostgreSQL($config) {
    $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
    try {
        $pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die("PostgreSQL Connection failed: " . $e->getMessage());
    }
}

function convertValue($value, $mysql_type) {
    global $type_conversions;
    
    if ($value === null) {
        return null;
    }
    
    // Handle different MySQL types
    if (strpos($mysql_type, 'int') !== false) {
        return (int)$value;
    } elseif (strpos($mysql_type, 'decimal') !== false || strpos($mysql_type, 'float') !== false) {
        return (float)$value;
    } elseif (strpos($mysql_type, 'tinyint(1)') !== false) {
        return (bool)$value;
    } elseif (strpos($mysql_type, 'datetime') !== false || strpos($mysql_type, 'timestamp') !== false) {
        return $value; // Keep as string, PostgreSQL will handle conversion
    } else {
        return $value; // Keep as string
    }
}

function migrateTable($mysql_conn, $pdo, $table_name) {
    echo "Migrating table: $table_name\n";
    
    // Check if table exists in MySQL
    $result = $mysql_conn->query("SHOW TABLES LIKE '$table_name'");
    if ($result->num_rows == 0) {
        echo "Table $table_name not found in MySQL, skipping...\n";
        return;
    }
    
    // Get table structure
    $structure = $mysql_conn->query("DESCRIBE $table_name");
    $columns = [];
    $column_types = [];
    
    while ($row = $structure->fetch_assoc()) {
        $columns[] = $row['Field'];
        $column_types[$row['Field']] = $row['Type'];
    }
    
    // Get data from MySQL
    $data = $mysql_conn->query("SELECT * FROM $table_name");
    
    if ($data && $data->num_rows > 0) {
        // Clear existing data in PostgreSQL (optional - comment out if you want to keep existing data)
        try {
            $pdo->exec("TRUNCATE TABLE $table_name RESTART IDENTITY CASCADE");
        } catch (PDOException $e) {
            echo "Warning: Could not truncate $table_name: " . $e->getMessage() . "\n";
        }
        
        $inserted_count = 0;
        
        // Insert data into PostgreSQL
        while ($row = $data->fetch_assoc()) {
            $values = [];
            $placeholders = [];
            
            foreach ($columns as $column) {
                if (isset($row[$column])) {
                    $converted_value = convertValue($row[$column], $column_types[$column]);
                    $values[] = $converted_value;
                    $placeholders[] = '?';
                } else {
                    $values[] = null;
                    $placeholders[] = '?';
                }
            }
            
            $columns_str = implode(', ', $columns);
            $placeholders_str = implode(', ', $placeholders);
            
            $insert_query = "INSERT INTO $table_name ($columns_str) VALUES ($placeholders_str)";
            
            try {
                $stmt = $pdo->prepare($insert_query);
                $stmt->execute($values);
                $inserted_count++;
            } catch (PDOException $e) {
                echo "Error inserting data into $table_name: " . $e->getMessage() . "\n";
                echo "Query: $insert_query\n";
                echo "Values: " . print_r($values, true) . "\n";
            }
        }
        
        echo "Successfully migrated $inserted_count rows from $table_name\n";
    } else {
        echo "No data found in $table_name\n";
    }
}

function main() {
    global $mysql_config, $postgres_config, $tables_to_migrate;
    
    echo "Starting MySQL to Neon PostgreSQL migration...\n\n";
    
    // Validate configuration
    if ($postgres_config['host'] === 'your-neon-host.neon.tech') {
        echo "ERROR: Please update the PostgreSQL configuration with your actual Neon database details!\n";
        echo "Edit the \$postgres_config array in this script with your Neon connection details.\n";
        exit(1);
    }
    
    // Connect to databases
    echo "Connecting to MySQL...\n";
    $mysql_conn = connectMySQL($mysql_config);
    
    echo "Connecting to PostgreSQL...\n";
    $pdo = connectPostgreSQL($postgres_config);
    
    echo "Both connections successful!\n\n";
    
    // Migrate each table
    foreach ($tables_to_migrate as $table) {
        migrateTable($mysql_conn, $pdo, $table);
        echo "\n";
    }
    
    // Close connections
    $mysql_conn->close();
    
    echo "Migration completed!\n";
    echo "Please verify your data in the Neon PostgreSQL database.\n";
}

// Run the migration
if (php_sapi_name() === 'cli') {
    main();
} else {
    echo "This script should be run from command line.\n";
    echo "Usage: php migrate_data_to_neon.php\n";
}
?>
