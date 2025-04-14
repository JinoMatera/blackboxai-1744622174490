<?php
require_once __DIR__.'/../config/db.php';
require_once __DIR__.'/product.php';

class AIRecommendation {
    private $pdo;
    private $product;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
        $this->product = new Product();
    }

    public function getRecommendations($userId, $limit = 4) {
        try {
            // Get user's purchase history
            $stmt = $this->pdo->prepare("
                SELECT product_id 
                FROM order_items oi
                JOIN orders o ON oi.order_id = o.id
                WHERE o.user_id = ?
                GROUP BY product_id
                ORDER BY COUNT(*) DESC
                LIMIT 3
            ");
            $stmt->execute([$userId]);
            $purchasedItems = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Get similar products based on categories of purchased items
            if (!empty($purchasedItems)) {
                $placeholders = implode(',', array_fill(0, count($purchasedItems), '?'));
                $stmt = $this->pdo->prepare("
                    SELECT p.* 
                    FROM products p
                    JOIN product_categories pc ON p.id = pc.product_id
                    WHERE pc.category_id IN (
                        SELECT category_id 
                        FROM product_categories 
                        WHERE product_id IN ($placeholders)
                    )
                    AND p.id NOT IN ($placeholders)
                    GROUP BY p.id
                    ORDER BY RAND()
                    LIMIT ?
                ");
                $params = array_merge($purchasedItems, $purchasedItems, [$limit]);
                $stmt->execute($params);
                return $stmt->fetchAll();
            }

            // Fallback to popular products if no purchase history
            return $this->product->getPopularProducts($limit);
        } catch (PDOException $e) {
            error_log("AI Recommendation failed: " . $e->getMessage());
            return $this->product->getPopularProducts($limit);
        }
    }

    public function getFrequentlyBoughtTogether($productId, $limit = 3) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT oi2.product_id, COUNT(*) as frequency
                FROM order_items oi1
                JOIN order_items oi2 ON oi1.order_id = oi2.order_id
                WHERE oi1.product_id = ? AND oi2.product_id != ?
                GROUP BY oi2.product_id
                ORDER BY frequency DESC
                LIMIT ?
            ");
            $stmt->execute([$productId, $productId, $limit]);
            $relatedIds = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

            if (!empty($relatedIds)) {
                $placeholders = implode(',', array_fill(0, count($relatedIds), '?'));
                $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
                $stmt->execute($relatedIds);
                return $stmt->fetchAll();
            }

            return [];
        } catch (PDOException $e) {
            error_log("Frequently bought together failed: " . $e->getMessage());
            return [];
        }
    }
}
