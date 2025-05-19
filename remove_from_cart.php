<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cartId'])) {
    $cartId = intval($_POST['cartId']);
    $userId = $_SESSION['id'];
    $sql = "DELETE FROM cart WHERE id = ? AND userId = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $cartId, $userId);
    mysqli_stmt_execute($stmt);
}

// Redirect back to the page the user was on
header('Location: ' . $_SERVER['HTTP_REFERER'] ?? 'index.php');
exit; 