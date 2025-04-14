<?php
require_once __DIR__.'/../includes/auth.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $result = registerUser($username, $email, $password);
    
    if ($result['success']) {
        $_SESSION['message'] = $result['message'];
        header('Location: login.php');
        exit();
    } else {
        $_SESSION['error'] = $result['message'];
        header('Location: signup.php');
        exit();
    }
} else {
    header('Location: signup.php');
    exit();
}
