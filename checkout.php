<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['id'];
$success = false;
$error = '';

// Process checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    mysqli_begin_transaction($conn);
    try {
        // Get cart items
        $cartQuery = "SELECT c.id as cartId, p.id as productId, p.name, p.price, c.quantity
                     FROM cart c
                     JOIN products p ON c.productId = p.id
                     WHERE c.userId = ?";
        $cartStmt = mysqli_prepare($conn, $cartQuery);
        mysqli_stmt_bind_param($cartStmt, 'i', $userId);
        mysqli_stmt_execute($cartStmt);
        $cartResult = mysqli_stmt_get_result($cartStmt);
        
        $totalAmount = 0;
        $cartItems = [];
        
        while ($item = mysqli_fetch_assoc($cartResult)) {
            $cartItems[] = $item;
            $totalAmount += $item['price'] * $item['quantity'];
        }
        
        if (count($cartItems) === 0) {
            throw new Exception('Your cart is empty');
        }
        
        // Create order
        $orderQuery = "INSERT INTO orders (userId, totalAmount, status) VALUES (?, ?, 'placed')";
        $orderStmt = mysqli_prepare($conn, $orderQuery);
        mysqli_stmt_bind_param($orderStmt, 'id', $userId, $totalAmount);
        mysqli_stmt_execute($orderStmt);
        $orderId = mysqli_insert_id($conn);
        
        // Add order items
        $itemQuery = "INSERT INTO orderItems (orderId, productId, quantity, pricePerUnit, subtotal) VALUES (?, ?, ?, ?, ?)";
        $itemStmt = mysqli_prepare($conn, $itemQuery);
        
        foreach ($cartItems as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            mysqli_stmt_bind_param($itemStmt, 'iiidi', $orderId, $item['productId'], $item['quantity'], $item['price'], $subtotal);
            mysqli_stmt_execute($itemStmt);
        }
        
        // Clear cart
        $clearCartQuery = "DELETE FROM cart WHERE userId = ?";
        $clearCartStmt = mysqli_prepare($conn, $clearCartQuery);
        mysqli_stmt_bind_param($clearCartStmt, 'i', $userId);
        mysqli_stmt_execute($clearCartStmt);
        
        mysqli_commit($conn);
        $success = true;
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error = $e->getMessage();
    }
}

// Get cart items for display
$cartQuery = "SELECT p.name, p.price, c.quantity
             FROM cart c
             JOIN products p ON c.productId = p.id
             WHERE c.userId = ?";
$cartStmt = mysqli_prepare($conn, $cartQuery);
mysqli_stmt_bind_param($cartStmt, 'i', $userId);
mysqli_stmt_execute($cartStmt);
$cartResult = mysqli_stmt_get_result($cartStmt);

$totalAmount = 0;
$cartItems = [];

while ($item = mysqli_fetch_assoc($cartResult)) {
    $cartItems[] = $item;
    $totalAmount += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Scent Haven</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: #f8f9fa;
            color: #333;
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 2rem;
            align-items: start;
        }
        .order-summary {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(30,55,153,0.08);
        }
        .order-items {
            margin-bottom: 2rem;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid #eee;
        }
        .item-details {
            flex: 1;
        }
        .item-name {
            font-weight: 500;
            color: #1e3799;
        }
        .item-quantity {
            font-size: 0.9em;
            color: #666;
        }
        .item-price {
            font-weight: 500;
            color: #1e3799;
        }
        .total {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 2px solid #eee;
            text-align: right;
            font-size: 1.2em;
            font-weight: 600;
            color: #1e3799;
        }
        .checkout-form {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(30,55,153,0.08);
        }
        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: #1e3799;
            margin-bottom: 1.5rem;
        }
        .checkout-btn {
            background: #1e3799;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            margin-top: 1rem;
            transition: background 0.3s ease;
        }
        .checkout-btn:hover {
            background: #4a69bd;
        }
        .success-message {
            text-align: center;
            padding: 2rem;
            background: #d4edda;
            color: #155724;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .error-message {
            text-align: center;
            padding: 2rem;
            background: #f8d7da;
            color: #721c24;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 2rem;
            color: #1e3799;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Home</a>
        
        <?php if ($success): ?>
            <div class="success-message">
                <h2><i class="fas fa-check-circle"></i> Order Placed Successfully!</h2>
                <p>Thank you for your purchase. Your order has been confirmed.</p>
                <a href="index.php" style="color:#155724;text-decoration:underline;">Continue Shopping</a>
            </div>
        <?php elseif ($error): ?>
            <div class="error-message">
                <h2><i class="fas fa-exclamation-circle"></i> Error</h2>
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php else: ?>
            <div class="checkout-grid">
                <div class="checkout-form">
                    <h1 class="form-title">Checkout</h1>
                    <form method="post" action="checkout.php">
                        <button type="submit" class="checkout-btn">Place Order</button>
                    </form>
                </div>
                
                <div class="order-summary">
                    <h2 style="font-family:'Playfair Display',serif;color:#1e3799;margin-top:0;">Order Summary</h2>
                    <div class="order-items">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="order-item">
                                <div class="item-details">
                                    <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                    <div class="item-quantity">Qty: <?php echo $item['quantity']; ?></div>
                                </div>
                                <div class="item-price">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="total">
                        Total: $<?php echo number_format($totalAmount, 2); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 