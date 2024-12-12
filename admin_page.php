<?php
// Database connection
$dsn = "mysql:host=localhost;dbname=finalproject;charset=utf8mb4";
$username = "root";
$password = "mysql";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['item_name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $file_path = $_POST['file_path'] ?? '';

    if (!empty($item_name) && is_numeric($price) && !empty($file_path)) {
        $stmt = $pdo->prepare("INSERT INTO items (item_name, price, file_path) VALUES (:item_name, :price, :file_path)");
        $stmt->bindParam(':item_name', $item_name, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_INT);
        $stmt->bindParam(':file_path', $file_path, PDO::PARAM_STR);

        try {
            $stmt->execute();
            echo "<p style='color: green;'>Item added successfully!</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Error adding item: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Please fill in all fields correctly.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daraan Games Store</title>

    <link rel="stylesheet" href="finalStyles.css">
    <link rel="icon" href="favicon.ico.png">
</head>
<body>
    <header>
        <div class="logo">
            <h1>Daraan Games</h1>
        </div>
        <nav class="navbar" style="background-color: #3b1e10;">
            <ul>
                <li><a href="index.html">🏠 Home</a></li>
                <li><a href="storeFront.php">🛒 Products</a></li>
                <li><a href="aboutUs.html">ℹ️ About</a></li>
            </ul>
        </nav>
    </header>
    <div class="auth-container">
        <h1>Add New Item</h1>
        <form method="POST" action="admin_page.php">
            <label for="item_name">Item Name:</label><br>
            <input type="text" id="item_name" name="item_name" required><br><br>

            <label for="price">Price:</label><br>
            <input type="number" id="price" name="price" required min="0" step="0.01"><br><br>

            <label for="file_path">File Path:</label><br>
            <input type="text" id="file_path" name="file_path" required><br><br>

            <button type="submit" class="button">Add Item</button>
        </form>
    </div>
</body>
</html>
