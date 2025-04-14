<?php
require_once __DIR__.'/../config/db.php';

try {
    // Sample products
    $products = [
        ['name' => 'Wireless Headphones', 'price' => 99.99, 'category' => 'electronics'],
        ['name' => 'Smart Watch', 'price' => 199.99, 'category' => 'electronics'],
        ['name' => 'Yoga Mat', 'price' => 29.99, 'category' => 'fitness'],
        ['name' => 'Water Bottle', 'price' => 19.99, 'category' => 'fitness'],
        ['name' => 'Running Shoes', 'price' => 89.99, 'category' => 'fitness']
    ];

    // Sample users
    $users = [
        ['username' => 'test_user1', 'email' => 'user1@test.com'],
        ['username' => 'test_user2', 'email' => 'user2@test.com']
    ];

    // Sample orders to establish purchase patterns
    $orders = [
        ['user_id' => 1, 'products' => [1, 2]], // User 1 buys electronics
        ['user_id' => 2, 'products' => [3, 4, 5]] // User 2 buys fitness items
    ];

    // Insert products
    foreach ($products as $product) {
        $stmt = $pdo->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
        $stmt->execute([$product['name'], $product['price']]);
        $productId = $pdo->lastInsertId();
        
        // Insert category
        $stmt = $pdo->prepare("INSERT INTO product_categories (product_id, category_id) 
                              VALUES (?, (SELECT id FROM categories WHERE name = ?))");
        $stmt->execute([$productId, $product['category']]);
    }

    // Insert users
    foreach ($users as $user) {
        $stmt = $pdo->prepare("INSERT INTO users (username, email) VALUES (?, ?)");
        $stmt->execute([$user['username'], $user['email']]);
    }

    // Insert orders
    foreach ($orders as $order) {
        $stmt = $pdo->prepare("INSERT INTO orders (user_id) VALUES (?)");
        $stmt->execute([$order['user_id']]);
        $orderId = $pdo->lastInsertId();
        
        // Insert order items
        foreach ($order['products'] as $productId) {
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price)
                                  VALUES (?, ?, 1, (SELECT price FROM products WHERE id = ?))");
            $stmt->execute([$orderId, $productId, $productId]);
        }
    }

    echo "Test data loaded successfully!\n";

} catch (PDOException $e) {
    echo "Error loading test data: " . $e->getMessage() . "\n";
}
