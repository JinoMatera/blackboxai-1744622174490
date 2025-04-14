<?php
require_once __DIR__.'/../config/db.php';
require_once __DIR__.'/../includes/product.php';
require_once __DIR__.'/../includes/ai_recommendation.php';
session_start();

$product = new Product();
$ai = new AIRecommendation();

// Get all products
$products = $product->getAllProducts();

// Get recommendations if user is logged in
$recommendations = [];
$frequentlyBought = [];
if (isset($_SESSION['user_id'])) {
    $recommendations = $ai->getRecommendations($_SESSION['user_id']);
    
    // For demo, get frequently bought with first product
    if (!empty($products)) {
        $frequentlyBought = $ai->getFrequentlyBoughtTogether($products[0]['id']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - eCommerce Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-indigo-600">Products</h1>
            <nav>
                <a href="index.php" class="px-4 py-2 text-gray-700 hover:text-indigo-600">Home</a>
                <a href="cart.php" class="px-4 py-2 text-gray-700 hover:text-indigo-600">Cart</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="logout.php" class="px-4 py-2 text-gray-700 hover:text-indigo-600">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="px-4 py-2 text-gray-700 hover:text-indigo-600">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <!-- AI Search -->
        <div class="mb-8">
            <form id="aiSearchForm" class="flex">
                <input type="text" name="query" placeholder="What are you looking for?" 
                    class="flex-1 border-gray-300 rounded-l-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-r-md hover:bg-indigo-700">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
            <div id="aiSearchResults" class="mt-4 hidden"></div>
        </div>

        <!-- Personalized Recommendations -->
        <?php if (!empty($recommendations)): ?>
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-4">Recommended For You</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <?php foreach ($recommendations as $product): ?>
                <div class="bg-white rounded-lg shadow p-4">
                    <img src="../uploads/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="h-32 w-full object-cover rounded">
                    <h3 class="text-lg font-bold mt-2"><?= $product['name'] ?></h3>
                    <p class="text-gray-600">$<?= number_format($product['price'], 2) ?></p>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="mt-2 bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700">Add to Cart</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Frequently Bought Together -->
        <?php if (!empty($frequentlyBought)): ?>
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-4">Frequently Bought Together</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <?php foreach ($frequentlyBought as $product): ?>
                <div class="bg-white rounded-lg shadow p-4">
                    <img src="../uploads/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="h-32 w-full object-cover rounded">
                    <h3 class="text-lg font-bold mt-2"><?= $product['name'] ?></h3>
                    <p class="text-gray-600">$<?= number_format($product['price'], 2) ?></p>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="mt-2 bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700">Add to Cart</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- All Products -->
        <section class="mb-12">
            <h2 class="text-3xl font-bold mb-6">Available Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-lg shadow p-4">
                    <img src="../uploads/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="h-40 w-full object-cover rounded">
                    <h3 class="text-lg font-bold mt-2"><?= $product['name'] ?></h3>
                    <p class="text-gray-600">$<?= number_format($product['price'], 2) ?></p>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit" class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Add to Cart</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <script>
        // AI Search functionality
        document.getElementById('aiSearchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const query = this.querySelector('input').value;
            
            fetch('ai_search.php?query=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    const resultsDiv = document.getElementById('aiSearchResults');
                    resultsDiv.innerHTML = '';
                    resultsDiv.classList.remove('hidden');
                    
                    if (data.length === 0) {
                        resultsDiv.innerHTML = '<p class="text-gray-600">No results found</p>';
                        return;
                    }
                    
                    const resultsList = document.createElement('div');
                    resultsList.className = 'grid grid-cols-1 md:grid-cols-3 gap-4';
                    
                    data.forEach(product => {
                        const productDiv = document.createElement('div');
                        productDiv.className = 'bg-white rounded-lg shadow p-4';
                        productDiv.innerHTML = `
                            <img src="../uploads/${product.image}" alt="${product.name}" class="h-32 w-full object-cover rounded">
                            <h3 class="text-lg font-bold mt-2">${product.name}</h3>
                            <p class="text-gray-600">$${product.price.toFixed(2)}</p>
                            <form action="add_to_cart.php" method="POST">
                                <input type="hidden" name="product_id" value="${product.id}">
                                <button type="submit" class="mt-2 bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700">Add to Cart</button>
                            </form>
                        `;
                        resultsList.appendChild(productDiv);
                    });
                    
                    resultsDiv.appendChild(resultsList);
                });
        });
    </script>
</body>
</html>
