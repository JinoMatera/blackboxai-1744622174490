<?php
require_once __DIR__.'/../config/db.php';

function registerUser($username, $email, $password) {
    global $pdo;
    
    // Check if username or email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    
    if ($stmt->rowCount() > 0) {
        return ['success' => false, 'message' => 'Username or email already exists'];
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'customer')");
    $result = $stmt->execute([$username, $email, $hashedPassword]);
    
    return ['success' => $result, 'message' => $result ? 'Registration successful' : 'Registration failed'];
}

function loginUser($username, $password) {
    global $pdo;
    
    // Get user by username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if (!$user || !password_verify($password, $user['password'])) {
        return ['success' => false, 'message' => 'Invalid username or password'];
    }
    
    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    
    return ['success' => true, 'message' => 'Login successful', 'role' => $user['role']];
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function logout() {
    session_unset();
    session_destroy();
}
