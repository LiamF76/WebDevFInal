<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "finalproject";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$item_name = $_POST['item_name'];
$price = intval($_POST['price']);

// Prepare and bind SQL statement
$stmt = $conn->prepare("INSERT INTO store (item_name, price) VALUES (?, ?)");
$stmt->bind_param("si", $item_name, $price);

// Execute the query
if ($stmt->execute()) {
    echo "Item added to cart successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close the connection
$stmt->close();
$conn->close();
?>