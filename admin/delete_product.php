<?php
require_once __DIR__.'/../config/db.php';
require_once __DIR__.'/../includes/product.php';
session_start();

if (isset($_GET['id'])) {
    $product = new Product();
    $result = $product->deleteProduct($_GET['id']);
    
    if ($result) {
        $_SESSION['message'] = 'Product deleted successfully!';
    } else {
        $_SESSION['error'] = 'Failed to delete product.';
    }
}

header('Location: products.php');
exit();
