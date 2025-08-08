<?php
session_start();

if ($_SESSION['role'] !== 'customer') {
    header('Location: login.php');
    exit();
}

echo "Welcome, customer " . $_SESSION['username'];
?>