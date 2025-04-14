<?php
require_once __DIR__.'/../config/db.php';

class Product {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAllProducts() {
        $stmt = $this->pdo->query("SELECT * FROM products");
        return $stmt->fetchAll();
    }

    public function getProductById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function addProduct($name, $description, $price, $image, $category) {
        $stmt = $this->pdo->prepare("INSERT INTO products (name, description, price, image, category) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $description, $price, $image, $category]);
    }

    public function updateProduct($id, $name, $description, $price, $image, $category) {
        $stmt = $this->pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ?, category = ? WHERE id = ?");
        return $stmt->execute([$name, $description, $price, $image, $category, $id]);
    }

    public function deleteProduct($id) {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getProductsByCategory($category) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE category = ?");
        $stmt->execute([$category]);
        return $stmt->fetchAll();
    }
}
