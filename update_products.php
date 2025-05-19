<?php
require_once 'config.php';

// Update product names to match what's shown on the website
$updates = [
    1 => 'Chanel N°5',
    2 => 'Miss Dior',
    3 => 'Black Orchid',
    4 => 'Sauvage',
    5 => 'Bleu de Chanel',
    6 => 'Neroli Portofino'
];

foreach ($updates as $id => $name) {
    $sql = "UPDATE products SET name = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $name, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "Updated product ID $id to '$name'<br>";
    } else {
        echo "Error updating product ID $id: " . mysqli_error($conn) . "<br>";
    }
}

// Verify all product names
$result = mysqli_query($conn, "SELECT id, name FROM products ORDER BY id");
if ($result) {
    echo "<br>Current product names:<br>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: {$row['id']} - Name: {$row['name']}<br>";
    }
} else {
    echo "Error checking products: " . mysqli_error($conn);
}

mysqli_close($conn);
echo "<br>Done!";
?> 