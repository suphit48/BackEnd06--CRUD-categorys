<?php
session_start();

if ($_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

echo "ยินดีต้อนรับ, คุณ " . $_SESSION['username'];
?>