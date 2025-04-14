<?php
require_once __DIR__.'/../includes/auth.php';
session_start();

logout();
header('Location: index.php');
exit();
