<?php
session_start();

// Simulate a test user login
$_SESSION['user_id'] = 1; // Assuming user ID 1 exists in the database
$_SESSION['username'] = 'testuser';
$_SESSION['role'] = 'customer';

header('Location: products.php'); // Redirect to products page
exit();
