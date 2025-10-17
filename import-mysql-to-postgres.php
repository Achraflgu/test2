<?php
// Convert MySQL schema to PostgreSQL and import data
// This script converts the msport.sql MySQL dump to PostgreSQL format

header('Content-Type: text/html; charset=utf-8');

try {
    include 'db_connection.php';
    
    echo '<h1>MSport Database Import</h1>';
    echo '<p>Converting MySQL schema to PostgreSQL...</p>';
    
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
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        
        try {
            $conn->exec($statement);
            $successCount++;
        } catch (Exception $e) {
            $errorCount++;
            echo '<p style="color: red;">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '<p style="color: gray;">Statement: ' . htmlspecialchars(substr($statement, 0, 100)) . '...</p>';
        }
    }
    
    echo '<h2>Import Results</h2>';
    echo '<p><strong>Successful statements:</strong> ' . $successCount . '</p>';
    echo '<p><strong>Failed statements:</strong> ' . $errorCount . '</p>';
    
    if ($errorCount === 0) {
        echo '<p style="color: green;"><strong>✅ Database import completed successfully!</strong></p>';
    } else {
        echo '<p style="color: orange;"><strong>⚠️ Import completed with some errors. Check the details above.</strong></p>';
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
    $sqlContent = str_replace('varchar(255)', 'VARCHAR(255)', $sqlContent);
    $sqlContent = str_replace('varchar(100)', 'VARCHAR(100)', $sqlContent);
    $sqlContent = str_replace('varchar(20)', 'VARCHAR(20)', $sqlContent);
    $sqlContent = str_replace('text', 'TEXT', $sqlContent);
    $sqlContent = str_replace('timestamp NOT NULL DEFAULT current_timestamp()', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP', $sqlContent);
    $sqlContent = str_replace('datetime', 'TIMESTAMP', $sqlContent);
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
    
    return $sqlContent;
}
?>
