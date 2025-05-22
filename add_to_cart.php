<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$userId = $_SESSION['id'];
$productId = isset($_POST['productId']) ? intval($_POST['productId']) : 0;

if ($productId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product']);
    exit;
}


$sql = "SELECT id, quantity FROM cart WHERE userId = ? AND productId = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ii', $userId, $productId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
   
    $newQty = $row['quantity'] + 1;
    $update = mysqli_prepare($conn, "UPDATE cart SET quantity = ? WHERE id = ?");
    mysqli_stmt_bind_param($update, 'ii', $newQty, $row['id']);
    mysqli_stmt_execute($update);
} else {
   
    $insert = mysqli_prepare($conn, "INSERT INTO cart (userId, productId, quantity) VALUES (?, ?, 1)");
    mysqli_stmt_bind_param($insert, 'ii', $userId, $productId);
    mysqli_stmt_execute($insert);
}


$countRes = mysqli_query($conn, "SELECT SUM(quantity) as total FROM cart WHERE userId = $userId");
$countRow = mysqli_fetch_assoc($countRes);
$total = $countRow['total'] ?? 0;

echo json_encode(['success' => true, 'cartCount' => $total]); 
