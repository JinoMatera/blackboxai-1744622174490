<?php
require_once __DIR__.'/../config/db.php';

class Cart {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function addToCart($userId, $productId, $quantity = 1) {
        // Check if item already exists in cart
        $stmt = $this->pdo->prepare("SELECT id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
        $existingItem = $stmt->fetch();

        if ($existingItem) {
            // Update quantity if item exists
            $newQuantity = $existingItem['quantity'] + $quantity;
            $stmt = $this->pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
            return $stmt->execute([$newQuantity, $existingItem['id']]);
        } else {
            // Add new item to cart
            $stmt = $this->pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
            return $stmt->execute([$userId, $productId, $quantity]);
        }
    }

    public function getCartItems($userId) {
        $stmt = $this->pdo->prepare("
            SELECT ci.*, p.name, p.price, p.image 
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function updateCartItem($itemId, $quantity) {
        if ($quantity <= 0) {
            return $this->removeFromCart($itemId);
        }
        $stmt = $this->pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
        return $stmt->execute([$quantity, $itemId]);
    }

    public function removeFromCart($itemId) {
        $stmt = $this->pdo->prepare("DELETE FROM cart_items WHERE id = ?");
        return $stmt->execute([$itemId]);
    }

    public function clearCart($userId) {
        $stmt = $this->pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }

    public function getCartTotal($userId) {
        $items = $this->getCartItems($userId);
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}
