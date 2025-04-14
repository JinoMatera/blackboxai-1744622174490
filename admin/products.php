<?php
require_once __DIR__.'/../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - eCommerce Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-indigo-600">Manage Products</h1>
            <nav>
                <a href="dashboard.php" class="px-4 py-2 text-gray-700 hover:text-indigo-600">Dashboard</a>
                <a href="orders.php" class="px-4 py-2 text-gray-700 hover:text-indigo-600">Orders</a>
                <a href="../public/index.php" class="px-4 py-2 text-gray-700 hover:text-indigo-600">View Store</a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <section class="mb-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold">Product List</h2>
                <a href="add_product.php" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    <i class="fas fa-plus mr-2"></i>Add Product
                </a>
            </div>
            
            <?php
            require_once __DIR__.'/../includes/product.php';
            $product = new Product();
            $products = $product->getAllProducts();
            
            if (isset($_SESSION['message'])) {
                echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">'.$_SESSION['message'].'</div>';
                unset($_SESSION['message']);
            }
            if (isset($_SESSION['error'])) {
                echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">'.$_SESSION['error'].'</div>';
                unset($_SESSION['error']);
            }
            ?>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $product['id'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="../uploads/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="h-10 w-10 rounded-full">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $product['name'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">$<?= number_format($product['price'], 2) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= ucfirst($product['category']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="edit_product.php?id=<?= $product['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <a href="delete_product.php?id=<?= $product['id'] ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4">
            <p>&copy; <?php echo date('Y'); ?> eCommerce Store. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
