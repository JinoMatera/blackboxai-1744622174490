<?php
require_once __DIR__.'/../includes/order.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$order = new Order();
$orderDetails = $order->getOrderDetails($_GET['id']);

if (!$orderDetails || $orderDetails['user_id'] != $_SESSION['user_id']) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - eCommerce Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-12">
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-3xl font-bold mb-6">Order Confirmation</h2>
            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-2">Order #<?= $orderDetails['id'] ?></h3>
                <p class="text-gray-600">Status: <span class="font-medium"><?= ucfirst($orderDetails['status']) ?></span></p>
                <p class="text-gray-600">Date: <?= date('F j, Y', strtotime($orderDetails['created_at'])) ?></p>
                <p class="text-gray-600">Total: $<?= number_format($orderDetails['total_amount'], 2) ?></p>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-2">Shipping Information</h3>
                <p class="text-gray-600"><?= $orderDetails['shipping_address'] ?></p>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-2">Order Items</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Product</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Price</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Quantity</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($orderDetails['items'] as $item): ?>
                            <tr>
                                <td class="px-4 py-2"><?= $item['name'] ?></td>
                                <td class="px-4 py-2">$<?= number_format($item['price'], 2) ?></td>
                                <td class="px-4 py-2"><?= $item['quantity'] ?></td>
                                <td class="px-4 py-2">$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <a href="index.php" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Continue Shopping
            </a>
        </div>
    </div>
</body>
</html>
