<?php
require_once __DIR__.'/../includes/cart.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $cart = new Cart();
    $result = $cart->removeFromCart($_GET['id']);

    if ($result) {
        $_SESSION['message'] = 'Item removed from cart successfully!';
    } else {
        $_SESSION['error'] = 'Failed to remove item from cart';
    }
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'cart.php'));
exit();
