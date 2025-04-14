<?php
try {
    // Load configuration from db.php
    require_once __DIR__.'/../config/db.php';
    
    // Attempt to create a PDO connection using configured credentials
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database connection successful!\n";
    
    // Test if PDO MySQL driver is available
    if (!in_array('mysql', PDO::getAvailableDrivers())) {
        echo "PDO MySQL driver is NOT available\n";
        echo "Available drivers: " . implode(', ', PDO::getAvailableDrivers()) . "\n";
    } else {
        echo "PDO MySQL driver is available\n";
    }
    
    // Simple query test
    $stmt = $pdo->query("SELECT 1");
    $result = $stmt->fetch();
    echo "Query test successful. Result: " . $result[0] . "\n";
    
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
    echo "Error Code: " . $e->getCode() . "\n";
    
    // Check for common error codes
    if ($e->getCode() == 2002) {
        echo "This typically means MySQL server is not running or host is incorrect\n";
    } elseif ($e->getCode() == 1045) {
        echo "This typically means invalid username/password\n";
    } elseif ($e->getCode() == 1049) {
        echo "This typically means the database doesn't exist\n";
    }
}
