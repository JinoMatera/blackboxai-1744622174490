<?php
require_once __DIR__.'/../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - eCommerce Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-indigo-600">Admin Dashboard</h1>
            <nav>
                <a href="products.php" class="px-4 py-2 text-gray-700 hover:text-indigo-600">Manage Products</a>
                <a href="orders.php" class="px-4 py-2 text-gray-700 hover:text-indigo-600">Manage Orders</a>
                <a href="../public/index.php" class="px-4 py-2 text-gray-700 hover:text-indigo-600">View Store</a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <section class="mb-12">
            <h2 class="text-3xl font-bold mb-6">Welcome to the Admin Dashboard</h2>
            <p>Use the navigation above to manage products and orders.</p>
        </section>
    </main>

    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4">
            <p>&copy; <?php echo date('Y'); ?> eCommerce Store. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
