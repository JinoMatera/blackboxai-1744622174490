<?php
require_once __DIR__.'/../includes/auth.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $result = loginUser($username, $password);
    
    if ($result['success']) {
        $_SESSION['message'] = $result['message'];
        if ($result['role'] === 'admin') {
            header('Location: ../admin/dashboard.php');
        } else {
            header('Location: index.php');
        }
        exit();
    } else {
        $_SESSION['error'] = $result['message'];
        header('Location: login.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
