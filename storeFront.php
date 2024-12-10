<?php
// Database connection
$dsn = 'mysql:host=localhost;dbname=finalproject;charset=utf8mb4';
$username = 'root';
$password = 'mysql';

try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $price = $_POST['price'];
    $itemName = "Preset Item Name"; // Example preset name

    if (!empty($price) && is_numeric($price) && $price > 0) {
        $stmt = $conn->prepare("INSERT INTO store (name, price) VALUES (:name, :price)");
        $stmt->bindParam(':name', $itemName);
        $stmt->bindParam(':price', $price);

        try {
            $stmt->execute();
            echo "<p>Item added successfully!</p>";
        } catch (PDOException $e) {
            echo "<p>Error adding item: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>Please enter a valid price.</p>";
    }
}
?>