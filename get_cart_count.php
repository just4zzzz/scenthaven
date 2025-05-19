<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    echo json_encode(['cartCount' => 0]);
    exit;
}
$userId = $_SESSION['id'];
$res = mysqli_query($conn, "SELECT SUM(quantity) as total FROM cart WHERE userId = $userId");
$row = mysqli_fetch_assoc($res);
$total = $row['total'] ?? 0;
echo json_encode(['cartCount' => $total]); 