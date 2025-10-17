<?php
/**
 * MySQL Data Export Script
 * This script exports data from your MySQL database in a format suitable for PostgreSQL import
 */

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'msport';

// Create MySQL connection
$mysqli = new mysqli($host, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Tables to export
$tables = [
    'admins',
    'brands', 
    'productcategories',
    'products',
    'customers',
    'orders',
    'order_items',
    'cart',
    'wishlist',
    'reviews',
    'coupons',
    'blogs',
    'contacts'
];

// Function to escape values for PostgreSQL
function escapeForPostgreSQL($value) {
    if ($value === null) {
        return 'NULL';
    }
    
    // Escape single quotes
    $value = str_replace("'", "''", $value);
    
    // Wrap in single quotes
    return "'" . $value . "'";
}

// Function to convert MySQL data types to PostgreSQL
function convertDataType($mysqlType, $value) {
    if ($value === null) {
        return 'NULL';
    }
    
    // Handle different data types
    if (strpos($mysqlType, 'int') !== false) {
        return (int)$value;
    } elseif (strpos($mysqlType, 'decimal') !== false || strpos($mysqlType, 'float') !== false) {
        return (float)$value;
    } elseif (strpos($mysqlType, 'tinyint(1)') !== false) {
        return $value ? 'true' : 'false';
    } else {
        return escapeForPostgreSQL($value);
    }
}

// Export data
$exportFile = 'mysql_export_' . date('Y-m-d_H-i-s') . '.sql';
$file = fopen($exportFile, 'w');

fwrite($file, "-- MySQL to PostgreSQL Data Export\n");
fwrite($file, "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n");

foreach ($tables as $table) {
    echo "Exporting table: $table\n";
    
    // Check if table exists
    $result = $mysqli->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows == 0) {
        echo "Table $table does not exist, skipping...\n";
        continue;
    }
    
    // Get table structure
    $structure = $mysqli->query("DESCRIBE $table");
    $columns = [];
    $columnTypes = [];
    
    while ($row = $structure->fetch_assoc()) {
        $columns[] = $row['Field'];
        $columnTypes[$row['Field']] = $row['Type'];
    }
    
    // Get data
    $data = $mysqli->query("SELECT * FROM $table");
    
    if ($data->num_rows > 0) {
        fwrite($file, "-- Data for table: $table\n");
        
        while ($row = $data->fetch_assoc()) {
            $values = [];
            foreach ($columns as $column) {
                $values[] = convertDataType($columnTypes[$column], $row[$column]);
            }
            
            $sql = "INSERT INTO $table (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
            fwrite($file, $sql);
        }
        
        fwrite($file, "\n");
        echo "Exported " . $data->num_rows . " rows from $table\n";
    } else {
        echo "No data found in $table\n";
    }
}

fclose($file);
$mysqli->close();

echo "\nExport completed! File saved as: $exportFile\n";
echo "\nNext steps:\n";
echo "1. Create your Neon PostgreSQL database\n";
echo "2. Run the schema creation from migrate_to_postgres.php\n";
echo "3. Import this data file into your PostgreSQL database\n";
echo "4. Update your application's database connection\n";
?>

