<?php
/**
 * Neon PostgreSQL Database Setup Script
 * This script helps you set up your Neon database with the MSport schema
 */

// Neon PostgreSQL configuration - Update with your actual Neon details
$neon_config = [
    'host' => 'your-neon-host.neon.tech',
    'port' => '5432',
    'database' => 'your-neon-database',
    'username' => 'your-neon-username',
    'password' => 'your-neon-password'
];

function connectToNeon($config) {
    $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
    try {
        $pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        echo "✅ Successfully connected to Neon PostgreSQL!\n";
        return $pdo;
    } catch (PDOException $e) {
        echo "❌ PostgreSQL Connection failed: " . $e->getMessage() . "\n";
        return false;
    }
}

function testConnection($pdo) {
    try {
        $stmt = $pdo->query("SELECT version()");
        $version = $stmt->fetchColumn();
        echo "📊 PostgreSQL Version: $version\n";
        
        $stmt = $pdo->query("SELECT current_database()");
        $database = $stmt->fetchColumn();
        echo "🗄️  Connected to database: $database\n";
        
        return true;
    } catch (PDOException $e) {
        echo "❌ Connection test failed: " . $e->getMessage() . "\n";
        return false;
    }
}

function checkTables($pdo) {
    try {
        $stmt = $pdo->query("
            SELECT table_name 
            FROM information_schema.tables 
            WHERE table_schema = 'public' 
            ORDER BY table_name
        ");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($tables)) {
            echo "📋 No tables found in the database.\n";
            echo "💡 You need to run the msport_postgresql.sql schema first.\n";
            return false;
        } else {
            echo "📋 Found " . count($tables) . " tables:\n";
            foreach ($tables as $table) {
                echo "   - $table\n";
            }
            return true;
        }
    } catch (PDOException $e) {
        echo "❌ Error checking tables: " . $e->getMessage() . "\n";
        return false;
    }
}

function showNextSteps() {
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "🚀 NEXT STEPS FOR NEON SETUP:\n";
    echo str_repeat("=", 60) . "\n\n";
    
    echo "1. 📝 Update your Neon configuration:\n";
    echo "   Edit the \$neon_config array in this script with your actual Neon details\n\n";
    
    echo "2. 🗄️  Create the database schema:\n";
    echo "   Run the msport_postgresql.sql file in your Neon database\n";
    echo "   You can do this through:\n";
    echo "   - Neon Console SQL Editor\n";
    echo "   - psql command line\n";
    echo "   - pgAdmin\n\n";
    
    echo "3. 📊 Migrate your data (optional):\n";
    echo "   If you have existing data, run: php migrate_data_to_neon.php\n\n";
    
    echo "4. 🔧 Update your application:\n";
    echo "   Update your db_connection.php to use the new PostgreSQL connection\n\n";
    
    echo "5. 🌐 Deploy to Railway:\n";
    echo "   Set the DATABASE_URL environment variable in Railway\n\n";
    
    echo "📚 For more help, check the DEPLOYMENT_GUIDE.md file\n";
}

function main() {
    global $neon_config;
    
    echo "🔧 Neon PostgreSQL Database Setup\n";
    echo str_repeat("=", 40) . "\n\n";
    
    // Check if configuration is updated
    if ($neon_config['host'] === 'your-neon-host.neon.tech') {
        echo "⚠️  Configuration not updated!\n\n";
        showNextSteps();
        return;
    }
    
    // Test connection
    echo "🔌 Testing connection to Neon...\n";
    $pdo = connectToNeon($neon_config);
    
    if (!$pdo) {
        echo "\n❌ Cannot connect to Neon database.\n";
        echo "Please check your configuration and try again.\n\n";
        showNextSteps();
        return;
    }
    
    echo "\n";
    
    // Test basic connection
    if (!testConnection($pdo)) {
        return;
    }
    
    echo "\n";
    
    // Check if tables exist
    if (!checkTables($pdo)) {
        echo "\n";
        showNextSteps();
        return;
    }
    
    echo "\n✅ Database setup looks good!\n";
    echo "🎉 Your Neon database is ready for the MSport application.\n\n";
    
    // Show some stats
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
        $product_count = $stmt->fetchColumn();
        echo "📦 Products in database: $product_count\n";
        
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM customers");
        $customer_count = $stmt->fetchColumn();
        echo "👥 Customers in database: $customer_count\n";
        
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders");
        $order_count = $stmt->fetchColumn();
        echo "🛒 Orders in database: $order_count\n";
        
    } catch (PDOException $e) {
        echo "ℹ️  Could not get table statistics: " . $e->getMessage() . "\n";
    }
    
    echo "\n🚀 Ready to deploy to Railway!\n";
}

// Run the setup
if (php_sapi_name() === 'cli') {
    main();
} else {
    echo "This script should be run from command line.\n";
    echo "Usage: php setup_neon_database.php\n";
}
?>

