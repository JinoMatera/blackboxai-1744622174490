<?php
require_once __DIR__.'/../includes/cart.php';
require_once __DIR__.'/../includes/auth.php';
session_start();

if (!isLoggedIn()) {
    $_SESSION['error'] = 'Please login to add items to cart';
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $userId = $_SESSION['user_id'];
    $productId = $_POST['product_id'];

    $cart = new Cart();
    $result = $cart->addToCart($userId, $productId);

    if ($result) {
        $_SESSION['message'] = 'Product added to cart successfully!';
    } else {
        $_SESSION['error'] = 'Failed to add product to cart';
    }
}

header('Location: ' . $_SERVER['HTTP_REFERER'] ?? 'products.php');
exit();
