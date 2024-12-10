<?php

$host = 'localhost'; 
$dbname = 'finalproject'; 
$user = 'root'; 
$pass = 'mysql';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

// Query to fetch items from the cart
$sql = "SELECT item_name, price FROM store";

// Initialize total price
$totalPrice = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="finalStyles.css">
</head>
<body>
    <div class="auth_container">
        <h2>Your Shopping Cart</h2>
        <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                    <td><?php echo number_format($row['price'], 2); ?> gold</td>
                </tr>
                <?php $totalPrice += $row['price']; ?>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="total">
            <p><strong>Total Price:</strong> <?php echo number_format($totalPrice, 2); ?> gold</p>
        </div>
        <?php else: ?>
        <p>Your cart is empty.</p>
        <?php endif; ?>
        <?php $conn->close(); ?>
    </div>
</body>
</html>