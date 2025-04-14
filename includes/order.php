<?php
require_once __DIR__.'/../config/db.php';

class Order {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function createOrder($userId, $items, $total, $shippingAddress, $paymentMethod) {
        try {
            $this->pdo->beginTransaction();

            // Create order record
            $stmt = $this->pdo->prepare("
                INSERT INTO orders (user_id, total_amount, shipping_address, payment_method, status) 
                VALUES (?, ?, ?, ?, 'pending')
            ");
            $stmt->execute([$userId, $total, $shippingAddress, $paymentMethod]);
            $orderId = $this->pdo->lastInsertId();

            // Add order items
            foreach ($items as $item) {
                $stmt = $this->pdo->prepare("
                    INSERT INTO order_items (order_id, product_id, quantity, price)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
            }

            $this->pdo->commit();
            return $orderId;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Order creation failed: " . $e->getMessage());
            return false;
        }
    }

    public function getOrdersByUser($userId) {
        $stmt = $this->pdo->prepare("
            SELECT o.*, 
                   COUNT(oi.id) as item_count,
                   SUM(oi.quantity) as total_items
            FROM orders o
            LEFT JOIN order_items oi ON o.id = oi.order_id
            WHERE o.user_id = ?
            GROUP BY o.id
            ORDER BY o.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getOrderDetails($orderId) {
        // Get order header
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch();

        if (!$order) return false;

        // Get order items
        $stmt = $this->pdo->prepare("
            SELECT oi.*, p.name, p.image
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        $order['items'] = $stmt->fetchAll();

        return $order;
    }

    public function updateOrderStatus($orderId, $status) {
        $stmt = $this->pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $orderId]);
    }
}
