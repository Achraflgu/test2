<?php
/**
 * Database Migration Script: MySQL to PostgreSQL
 * Run this script to convert your MySQL database to PostgreSQL format
 */

require_once 'db_connection_prod.php';

// MySQL to PostgreSQL data type mapping
$typeMapping = [
    'int(11)' => 'INTEGER',
    'varchar(255)' => 'VARCHAR(255)',
    'varchar(100)' => 'VARCHAR(100)',
    'varchar(20)' => 'VARCHAR(20)',
    'text' => 'TEXT',
    'longtext' => 'TEXT',
    'datetime' => 'TIMESTAMP',
    'date' => 'DATE',
    'time' => 'TIME',
    'decimal(10,2)' => 'DECIMAL(10,2)',
    'float' => 'REAL',
    'double' => 'DOUBLE PRECISION',
    'tinyint(1)' => 'BOOLEAN',
    'enum' => 'VARCHAR',
    'timestamp' => 'TIMESTAMP',
    'json' => 'JSONB'
];

// Function to convert MySQL CREATE TABLE to PostgreSQL
function convertMySQLToPostgreSQL($mysqlSQL) {
    global $typeMapping;
    
    // Remove MySQL specific syntax
    $postgresSQL = $mysqlSQL;
    
    // Convert data types
    foreach ($typeMapping as $mysqlType => $postgresType) {
        $postgresSQL = preg_replace('/\b' . preg_quote($mysqlType, '/') . '\b/i', $postgresType, $postgresSQL);
    }
    
    // Remove MySQL specific options
    $postgresSQL = preg_replace('/ENGINE=\w+/i', '', $postgresSQL);
    $postgresSQL = preg_replace('/DEFAULT CHARSET=\w+/i', '', $postgresSQL);
    $postgresSQL = preg_replace('/COLLATE=\w+/i', '', $postgresSQL);
    $postgresSQL = preg_replace('/AUTO_INCREMENT=\d+/i', '', $postgresSQL);
    
    // Convert AUTO_INCREMENT to SERIAL
    $postgresSQL = preg_replace('/AUTO_INCREMENT/i', 'SERIAL', $postgresSQL);
    
    // Remove trailing commas
    $postgresSQL = preg_replace('/,\s*\)/', ')', $postgresSQL);
    
    return $postgresSQL;
}

// Function to create PostgreSQL schema
function createPostgreSQLSchema($pdo) {
    $schema = "
    -- Create database schema for MSport E-commerce
    
    -- Admins table
    CREATE TABLE IF NOT EXISTS admins (
        admin_id SERIAL PRIMARY KEY,
        admin_name VARCHAR(100),
        admin_photo TEXT,
        admin_email VARCHAR(100),
        admin_password VARCHAR(100),
        admin_job VARCHAR(100),
        admin_phone VARCHAR(20)
    );
    
    -- Brands table
    CREATE TABLE IF NOT EXISTS brands (
        brand_id SERIAL PRIMARY KEY,
        brand_name VARCHAR(100),
        brand_photo TEXT,
        brand_status VARCHAR(20) DEFAULT 'active'
    );
    
    -- Product categories table
    CREATE TABLE IF NOT EXISTS productcategories (
        pcategory_id SERIAL PRIMARY KEY,
        pcategory_name VARCHAR(100),
        pcategory_photo TEXT,
        pcategory_status VARCHAR(20) DEFAULT 'active'
    );
    
    -- Products table
    CREATE TABLE IF NOT EXISTS products (
        product_id SERIAL PRIMARY KEY,
        product_name VARCHAR(255),
        product_description TEXT,
        product_price DECIMAL(10,2),
        product_sale_price DECIMAL(10,2),
        product_photo TEXT,
        product_stock_quantity INTEGER DEFAULT 0,
        product_status VARCHAR(20) DEFAULT 'active',
        product_tag VARCHAR(50),
        product_keywords TEXT,
        pcategory_id INTEGER REFERENCES productcategories(pcategory_id),
        brand_id INTEGER REFERENCES brands(brand_id),
        sale_start_date DATE,
        sale_end_date DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    
    -- Customers table
    CREATE TABLE IF NOT EXISTS customers (
        customer_id SERIAL PRIMARY KEY,
        customer_name VARCHAR(100),
        customer_email VARCHAR(100),
        customer_password VARCHAR(255),
        customer_phone VARCHAR(20),
        customer_address TEXT,
        customer_status VARCHAR(20) DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    
    -- Orders table
    CREATE TABLE IF NOT EXISTS orders (
        order_id SERIAL PRIMARY KEY,
        customer_id INTEGER REFERENCES customers(customer_id),
        order_total DECIMAL(10,2),
        order_status VARCHAR(50) DEFAULT 'pending',
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        shipping_address TEXT,
        payment_method VARCHAR(50),
        payment_status VARCHAR(50) DEFAULT 'pending'
    );
    
    -- Order items table
    CREATE TABLE IF NOT EXISTS order_items (
        order_item_id SERIAL PRIMARY KEY,
        order_id INTEGER REFERENCES orders(order_id),
        product_id INTEGER REFERENCES products(product_id),
        quantity INTEGER,
        price DECIMAL(10,2)
    );
    
    -- Cart table
    CREATE TABLE IF NOT EXISTS cart (
        cart_id SERIAL PRIMARY KEY,
        customer_id INTEGER REFERENCES customers(customer_id),
        product_id INTEGER REFERENCES products(product_id),
        quantity INTEGER DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    
    -- Wishlist table
    CREATE TABLE IF NOT EXISTS wishlist (
        wishlist_id SERIAL PRIMARY KEY,
        customer_id INTEGER REFERENCES customers(customer_id),
        product_id INTEGER REFERENCES products(product_id),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    
    -- Reviews table
    CREATE TABLE IF NOT EXISTS reviews (
        review_id SERIAL PRIMARY KEY,
        customer_id INTEGER REFERENCES customers(customer_id),
        product_id INTEGER REFERENCES products(product_id),
        rating INTEGER CHECK (rating >= 1 AND rating <= 5),
        review_text TEXT,
        review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        review_status VARCHAR(20) DEFAULT 'approved'
    );
    
    -- Coupons table
    CREATE TABLE IF NOT EXISTS coupons (
        coupon_id SERIAL PRIMARY KEY,
        coupon_code VARCHAR(50) UNIQUE,
        coupon_type VARCHAR(20),
        coupon_value DECIMAL(10,2),
        coupon_minimum_amount DECIMAL(10,2),
        coupon_maximum_discount DECIMAL(10,2),
        coupon_start_date DATE,
        coupon_end_date DATE,
        coupon_usage_limit INTEGER,
        coupon_used_count INTEGER DEFAULT 0,
        coupon_status VARCHAR(20) DEFAULT 'active'
    );
    
    -- Blogs table
    CREATE TABLE IF NOT EXISTS blogs (
        blog_id SERIAL PRIMARY KEY,
        blog_title VARCHAR(255),
        blog_content TEXT,
        blog_photo TEXT,
        blog_author VARCHAR(100),
        blog_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        blog_status VARCHAR(20) DEFAULT 'published'
    );
    
    -- Contacts table
    CREATE TABLE IF NOT EXISTS contacts (
        contact_id SERIAL PRIMARY KEY,
        contact_name VARCHAR(100),
        contact_email VARCHAR(100),
        contact_subject VARCHAR(255),
        contact_message TEXT,
        contact_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        contact_status VARCHAR(20) DEFAULT 'unread'
    );
    
    -- Create indexes for better performance
    CREATE INDEX IF NOT EXISTS idx_products_category ON products(pcategory_id);
    CREATE INDEX IF NOT EXISTS idx_products_brand ON products(brand_id);
    CREATE INDEX IF NOT EXISTS idx_products_status ON products(product_status);
    CREATE INDEX IF NOT EXISTS idx_orders_customer ON orders(customer_id);
    CREATE INDEX IF NOT EXISTS idx_orders_status ON orders(order_status);
    CREATE INDEX IF NOT EXISTS idx_cart_customer ON cart(customer_id);
    CREATE INDEX IF NOT EXISTS idx_wishlist_customer ON wishlist(customer_id);
    CREATE INDEX IF NOT EXISTS idx_reviews_product ON reviews(product_id);
    CREATE INDEX IF NOT EXISTS idx_coupons_code ON coupons(coupon_code);
    ";
    
    try {
        $pdo->exec($schema);
        echo "PostgreSQL schema created successfully!\n";
        return true;
    } catch (PDOException $e) {
        echo "Error creating schema: " . $e->getMessage() . "\n";
        return false;
    }
}

// Function to migrate data from MySQL to PostgreSQL
function migrateData($mysqlConn, $pdo) {
    $tables = [
        'admins', 'brands', 'productcategories', 'products', 'customers', 
        'orders', 'order_items', 'cart', 'wishlist', 'reviews', 
        'coupons', 'blogs', 'contacts'
    ];
    
    foreach ($tables as $table) {
        echo "Migrating table: $table\n";
        
        // Check if table exists in MySQL
        $checkQuery = "SHOW TABLES LIKE '$table'";
        $result = mysqli_query($mysqlConn, $checkQuery);
        
        if (mysqli_num_rows($result) > 0) {
            // Get data from MySQL
            $selectQuery = "SELECT * FROM $table";
            $result = mysqli_query($mysqlConn, $selectQuery);
            
            if ($result) {
                // Clear existing data in PostgreSQL
                $pdo->exec("TRUNCATE TABLE $table RESTART IDENTITY CASCADE");
                
                // Insert data into PostgreSQL
                while ($row = mysqli_fetch_assoc($result)) {
                    $columns = implode(', ', array_keys($row));
                    $placeholders = ':' . implode(', :', array_keys($row));
                    
                    $insertQuery = "INSERT INTO $table ($columns) VALUES ($placeholders)";
                    $stmt = $pdo->prepare($insertQuery);
                    
                    try {
                        $stmt->execute($row);
                    } catch (PDOException $e) {
                        echo "Error inserting data into $table: " . $e->getMessage() . "\n";
                    }
                }
                echo "Migrated " . mysqli_num_rows($result) . " rows from $table\n";
            }
        } else {
            echo "Table $table not found in MySQL database\n";
        }
    }
}

// Main migration process
if (php_sapi_name() === 'cli') {
    echo "Starting MySQL to PostgreSQL migration...\n";
    
    // Create PostgreSQL connection
    $config = getDatabaseConfig();
    if ($config['type'] !== 'postgresql') {
        echo "This script should be run with PostgreSQL configuration.\n";
        exit(1);
    }
    
    $pdo = getConnection();
    
    // Create schema
    if (createPostgreSQLSchema($pdo)) {
        echo "Schema creation completed.\n";
        
        // For data migration, you would need to:
        // 1. Export data from your local MySQL database
        // 2. Import it into PostgreSQL
        // 3. Or run this script with both MySQL and PostgreSQL connections
        
        echo "Migration script completed!\n";
        echo "Next steps:\n";
        echo "1. Export your MySQL data using mysqldump\n";
        echo "2. Import the data into your Neon PostgreSQL database\n";
        echo "3. Update your application to use the new database connection\n";
    }
} else {
    echo "This script should be run from command line.\n";
}
?>

