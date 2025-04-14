<?php
require_once __DIR__.'/../config/db.php';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Database Connection Successful!</h2>";
    echo "<p>PDO MySQL driver is available</p>";
    
    // Test query
    $stmt = $pdo->query("SELECT 1");
    $result = $stmt->fetch();
    echo "<p>Query test successful. Result: " . $result[0] . "</p>";
    
} catch (PDOException $e) {
    echo "<h2>Database Connection Failed</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p>Code: " . $e->getCode() . "</p>";
    
    if (!in_array('mysql', PDO::getAvailableDrivers())) {
        echo "<p>PDO MySQL driver is NOT available</p>";
        echo "<p>Loaded PHP modules: " . implode(', ', get_loaded_extensions()) . "</p>";
    }
}
