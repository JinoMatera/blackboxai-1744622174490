<?php
require_once __DIR__.'/../includes/product.php';
require_once __DIR__.'/../includes/ai_recommendation.php';
header('Content-Type: application/json');

if (isset($_GET['query'])) {
    $query = trim($_GET['query']);
    $product = new Product();
    $ai = new AIRecommendation();
    
    // First try exact matches
    $results = $product->searchProducts($query);
    
    // If no exact matches, use AI recommendations
    if (empty($results)) {
        session_start();
        $userId = $_SESSION['user_id'] ?? null;
        $results = $ai->getRecommendations($userId, 6);
    }
    
    echo json_encode($results);
    exit();
}

echo json_encode([]);
