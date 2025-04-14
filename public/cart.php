<?php
require_once __DIR__.'/../config/db.php';
require_once __DIR__.'/../includes/cart.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - eCommerce Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-indigo-600">Shopping Cart</h1>
            <nav>
                <a href="index.php" class="px-4 py-2 text-gray-700 hover:text-indigo-600">Home</a>
                <a href="products.php" class="px-4 py-2 text-gray-700 hover:text-indigo-600">Products</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="logout.php" class="px-4 py-2 text-gray-700 hover:text-indigo-600">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="px-4 py-2 text-gray-700 hover:text-indigo-600">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <section class="mb-12">
            <h2 class="text-3xl font-bold mb-6">Your Cart</h2>
            <?php
            if (isset($_SESSION['message'])) {
                echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">'.$_SESSION['message'].'</div>';
                unset($_SESSION['message']);
            }
            if (isset($_SESSION['error'])) {
                echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">'.$_SESSION['error'].'</div>';
                unset($_SESSION['error']);
            }

            if (!isset($_SESSION['user_id'])) {
                echo '<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">Please login to view your cart</div>';
            } else {
                $cart = new Cart();
                $items = $cart->getCartItems($_SESSION['user_id']);
                $total = $cart->getCartTotal($_SESSION['user_id']);
            ?>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img src="../uploads/<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="h-10 w-10 rounded-full">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900"><?= $item['name'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$<?= number_format($item['price'], 2) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="update_cart.php" method="POST" class="flex">
                                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="w-16 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <button type="submit" class="ml-2 bg-indigo-600 text-white px-2 py-1 rounded hover:bg-indigo-700">Update</button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="remove_from_cart.php?id=<?= $item['id'] ?>" class="text-red-600 hover:text-red-900">Remove</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="px-6 py-4 bg-gray-50 text-right">
                    <span class="text-lg font-bold">Total: $<?= number_format($total, 2) ?></span>
                    <a href="checkout.php" class="ml-4 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Proceed to Checkout</a>
                </div>
            </div>
            <?php } ?>
        </section>
    </main>
</body>
</html>
