<?php
// Complete MySQL to PostgreSQL Data Import Script
// This script converts and imports ALL data from msport.sql

header('Content-Type: text/html; charset=utf-8');

try {
    include 'db_connection.php';
    
    echo '<h1>MSport Complete Database Import</h1>';
    echo '<p>Converting and importing ALL data from MySQL to PostgreSQL...</p>';
    
    // Read the MySQL SQL file
    $sqlFile = 'MSPORT/msport.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception('SQL file not found: ' . $sqlFile);
    }
    
    $sqlContent = file_get_contents($sqlFile);
    
    // Convert MySQL to PostgreSQL
    $postgresqlContent = convertMySQLToPostgreSQL($sqlContent);
    
    // Split into individual statements
    $statements = explode(';', $postgresqlContent);
    
    $successCount = 0;
    $errorCount = 0;
    $totalStatements = 0;
    
    echo '<h2>Import Progress</h2>';
    echo '<div style="background: #f0f0f0; padding: 10px; border-radius: 5px;">';
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        
        $totalStatements++;
        
        try {
            $conn->exec($statement);
            $successCount++;
            
            // Show progress for every 10 statements
            if ($successCount % 10 == 0) {
                echo '<p>✅ Imported ' . $successCount . ' statements...</p>';
                flush();
            }
            
        } catch (Exception $e) {
            $errorCount++;
            echo '<p style="color: red;">❌ Error in statement ' . $totalStatements . ': ' . htmlspecialchars($e->getMessage()) . '</p>';
            
            // Show the problematic statement (first 100 chars)
            echo '<p style="color: gray; font-size: 12px;">Statement: ' . htmlspecialchars(substr($statement, 0, 100)) . '...</p>';
        }
    }
    
    echo '</div>';
    
    echo '<h2>Import Results</h2>';
    echo '<div style="background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 10px 0;">';
    echo '<p><strong>Total statements processed:</strong> ' . $totalStatements . '</p>';
    echo '<p><strong>Successful statements:</strong> ' . $successCount . '</p>';
    echo '<p><strong>Failed statements:</strong> ' . $errorCount . '</p>';
    echo '</div>';
    
    if ($errorCount === 0) {
        echo '<p style="color: green; font-size: 18px; font-weight: bold;">🎉 Database import completed successfully!</p>';
        echo '<p>All your data including dates, products, orders, customers, and reviews have been imported.</p>';
    } else {
        echo '<p style="color: orange; font-size: 18px; font-weight: bold;">⚠️ Import completed with some errors.</p>';
        echo '<p>Most data was imported successfully. Check the errors above for details.</p>';
    }
    
    // Show summary of imported data
    echo '<h2>Data Summary</h2>';
    try {
        $tables = ['admins', 'blog', 'blogreviews', 'brands', 'cart', 'contact', 'coupons', 'customers', 'delivery_methods', 'orderitems', 'orders', 'order_details', 'payments', 'payment_methods', 'productcategories', 'productreviews', 'products', 'product_coupons', 'settings', 'shipping_info', 'shoppingcart', 'wishlist'];
        
        echo '<table border="1" style="border-collapse: collapse; width: 100%;">';
        echo '<tr><th>Table</th><th>Records</th></tr>';
        
        foreach ($tables as $table) {
            try {
                $result = $conn->query("SELECT COUNT(*) as count FROM $table");
                $row = $result->fetch(PDO::FETCH_ASSOC);
                echo '<tr><td>' . $table . '</td><td>' . $row['count'] . '</td></tr>';
            } catch (Exception $e) {
                echo '<tr><td>' . $table . '</td><td style="color: red;">Error</td></tr>';
            }
        }
        echo '</table>';
        
    } catch (Exception $e) {
        echo '<p>Could not generate data summary: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    
} catch (Exception $e) {
    echo '<h1>Import Error</h1>';
    echo '<p><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
}

function convertMySQLToPostgreSQL($sqlContent) {
    // Remove MySQL-specific comments and settings
    $sqlContent = preg_replace('/-- phpMyAdmin SQL Dump.*?-- Database: `[^`]+`/s', '', $sqlContent);
    $sqlContent = preg_replace('/SET SQL_MODE[^;]+;/', '', $sqlContent);
    $sqlContent = preg_replace('/START TRANSACTION;/', '', $sqlContent);
    $sqlContent = preg_replace('/SET time_zone[^;]+;/', '', $sqlContent);
    $sqlContent = preg_replace('/\/\*!40101[^*]*\*\/;/', '', $sqlContent);
    $sqlContent = preg_replace('/COMMIT;/', '', $sqlContent);
    
    // Convert MySQL data types to PostgreSQL
    $sqlContent = str_replace('int(11)', 'INTEGER', $sqlContent);
    $sqlContent = str_replace('tinyint(1)', 'INTEGER', $sqlContent);
    $sqlContent = str_replace('varchar(255)', 'VARCHAR(255)', $sqlContent);
    $sqlContent = str_replace('varchar(100)', 'VARCHAR(100)', $sqlContent);
    $sqlContent = str_replace('varchar(50)', 'VARCHAR(50)', $sqlContent);
    $sqlContent = str_replace('varchar(20)', 'VARCHAR(20)', $sqlContent);
    $sqlContent = str_replace('text', 'TEXT', $sqlContent);
    $sqlContent = str_replace('timestamp NOT NULL DEFAULT current_timestamp()', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP', $sqlContent);
    $sqlContent = str_replace('datetime', 'TIMESTAMP', $sqlContent);
    $sqlContent = str_replace('date', 'DATE', $sqlContent);
    $sqlContent = str_replace('decimal(10,2)', 'DECIMAL(10,2)', $sqlContent);
    
    // Remove MySQL-specific engine and charset
    $sqlContent = preg_replace('/ENGINE=InnoDB[^;]*/', '', $sqlContent);
    $sqlContent = preg_replace('/DEFAULT CHARSET=[^;]*/', '', $sqlContent);
    $sqlContent = preg_replace('/COLLATE=[^;]*/', '', $sqlContent);
    
    // Convert AUTO_INCREMENT to SERIAL
    $sqlContent = preg_replace('/AUTO_INCREMENT/', 'SERIAL', $sqlContent);
    
    // Convert MySQL backticks to PostgreSQL double quotes
    $sqlContent = str_replace('`', '"', $sqlContent);
    
    // Convert MySQL INSERT syntax
    $sqlContent = preg_replace('/INSERT INTO "([^"]+)"/', 'INSERT INTO \1', $sqlContent);
    
    // Remove MySQL-specific table options
    $sqlContent = preg_replace('/AUTO_INCREMENT=\d+/', '', $sqlContent);
    
    // Convert MySQL functions
    $sqlContent = str_replace('current_timestamp()', 'CURRENT_TIMESTAMP', $sqlContent);
    
    // Handle NULL values properly
    $sqlContent = str_replace("'NULL'", 'NULL', $sqlContent);
    
    return $sqlContent;
}
?>
