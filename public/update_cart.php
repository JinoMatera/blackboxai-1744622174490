<?php
require_once __DIR__.'/../includes/cart.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id']) && isset($_POST['quantity'])) {
    $cart = new Cart();
    $result = $cart->updateCartItem($_POST['item_id'], $_POST['quantity']);

    if ($result) {
        $_SESSION['message'] = 'Cart updated successfully!';
    } else {
        $_SESSION['error'] = 'Failed to update cart item';
    }
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'cart.php'));
exit();
