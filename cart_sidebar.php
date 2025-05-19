<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    echo '<div style="text-align:center;color:#888;">Please <a href="login.php">login</a> to view your cart.</div>';
    exit;
}
$userId = $_SESSION['id'];
$sql = "SELECT c.id as cartId, p.id as productId, p.name, p.price, p.imageUrl, c.quantity
        FROM cart c
        JOIN products p ON c.productId = p.id
        WHERE c.userId = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total = 0;
$cartItems = [];
while ($row = mysqli_fetch_assoc($result)) {
    $cartItems[] = $row;
    $total += $row['price'] * $row['quantity'];
}
if (count($cartItems) === 0) {
    echo '<div style="text-align:center;color:#888;">Your cart is empty.</div>';
    exit;
}
echo '<div style="display:flex;flex-direction:column;gap:1.2rem;">';
foreach ($cartItems as $item) {
    echo '<div style="display:flex;align-items:center;gap:1rem;border-bottom:1px solid #f1f2f6;padding-bottom:1rem;">';
    echo '<img src="'.htmlspecialchars($item['imageUrl']).'" alt="'.htmlspecialchars($item['name']).'" style="width:70px;height:70px;object-fit:contain;border-radius:8px;background:#f9f9f9;">';
    echo '<div style="flex:1;">';
    echo '<div style="font-weight:600;color:#1e3799;">'.htmlspecialchars($item['name']).'</div>';
    echo '<div style="font-size:0.95em;color:#888;">Qty: '.$item['quantity'].'</div>';
    echo '<div style="font-size:1em;color:#1e3799;">$'.number_format($item['price'],2).'</div>';
    echo '</div>';
    echo '<form method="post" action="remove_from_cart.php" style="margin:0;">';
    echo '<input type="hidden" name="cartId" value="'.$item['cartId'].'">';
    echo '<button type="submit" style="background:none;border:none;color:#e74c3c;font-size:1.2em;cursor:pointer;"><i class="fas fa-trash"></i></button>';
    echo '</form>';
    echo '</div>';
}
echo '</div>';
echo '<div style="margin-top:1.5rem;text-align:right;font-weight:600;color:#1e3799;font-size:1.1em;">Total: $'.number_format($total,2).'</div>'; 