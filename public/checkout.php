<?php
require_once __DIR__.'/../includes/cart.php';
require_once __DIR__.'/../includes/order.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$cart = new Cart();
$items = $cart->getCartItems($_SESSION['user_id']);
$total = $cart->getCartTotal($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shippingAddress = trim($_POST['shipping_address']);
    $paymentMethod = trim($_POST['payment_method']);

    $order = new Order();
    $orderId = $order->createOrder($_SESSION['user_id'], $items, $total, $shippingAddress, $paymentMethod);

    if ($orderId) {
        $cart->clearCart($_SESSION['user_id']);
        $_SESSION['message'] = 'Order placed successfully!';
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['error'] = 'Failed to place order.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - eCommerce Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-12">
        <h2 class="text-3xl font-bold mb-6">Checkout</h2>
        <form action="checkout.php" method="POST" class="bg-white p-6 rounded shadow">
            <div class="mb-4">
                <label for="shipping_address" class="block text-sm font-medium text-gray-700">Shipping Address</label>
                <input type="text" name="shipping_address" id="shipping_address" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                <select name="payment_method" id="payment_method" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="credit_card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">Place Order</button>
        </form>
        <h3 class="text-2xl font-bold mt-6">Your Cart</h3>
        <div class="bg-white rounded-lg shadow overflow-hidden mt-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><?= $item['name'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">$<?= number_format($item['price'], 2) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= $item['quantity'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="px-6 py-4 bg-gray-50 text-right">
                <span class="text-lg font-bold">Total: $<?= number_format($total, 2) ?></span>
            </div>
        </div>
    </div>
</body>
</html>
