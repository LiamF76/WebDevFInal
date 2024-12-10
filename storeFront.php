<?php

session_start();
require_once 'auth.php';

// Check if user is logged in
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

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

// Handle book search
$search_results = null;
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = '%' . $_GET['search'] . '%';
    $search_sql = 'SELECT item_id, item_name, price FROM store WHERE item_name LIKE :search';
    $search_stmt = $pdo->prepare($search_sql);
    $search_stmt->execute(['search' => $search_term]);
    $search_results = $search_stmt->fetchAll();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['item_name']) && isset($_POST['price'])) {
        // Insert new entry
        $item_name = htmlspecialchars($_POST['item_name']);
        $price = htmlspecialchars($_POST['price']);
        
        $insert_sql = 'INSERT INTO store (item_name, price) VALUES (:item_name, :price)';
        $stmt_insert = $pdo->prepare($insert_sql);
        $stmt_insert->execute(['item_name' => $item_name, 'price' => $price]);
    } elseif (isset($_POST['delete_id'])) {
        // Delete an entry
        $delete_id = (int) $_POST['delete_id'];
        
        $delete_sql = 'DELETE FROM store WHERE item_id = :item_id';
        $stmt_delete = $pdo->prepare($delete_sql);
        $stmt_delete->execute(['item_id' => $delete_id]);
    }
}

// Get all books for main table
$sql = 'SELECT item_id, item_name, price FROM store';
$stmt = $pdo->query($sql);
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
            <h1>My Store</h1>
        </div>
        <nav class="navbar" style="background-color: #3b1e10;">
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="storeFront.php">Products</a></li>
                <li><a href="aboutUs.html">About Us</a></li>
            </ul>
        </nav>
    </header>
    <main>
    <!-- Hero Section -->
    <div class="hero">
        <p class="hero-h2">"I give to them not because I must, but because they deserve it. As king, what do I have that is not given to me by them? - Lazarus of Praetoria"</p>

    <!-- Table section with container -->
    <div class="table-container">
    <h2>Available Items</h2>
    <li><a href="yourCart.php">Your Cart</a></li>

    <!-- Table for submitting predefined item name and price -->
    <table border="1">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Name Your Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <p>Western Escarth Map</p>
                    <input type="hidden" id="item_name" name="item_name" value="Western Escarth Map" readonly>
                </td>
                <td>
                    <input type="number" id="price" name="price" required>
                </td>
                <td>
                    <button class="add-to-cart-btn" id="submitBtn">Add to Cart</button>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Drok Shah Map</p>
                    <input type="hidden" id="item_name" name="item_name" value="Drok Shah Map" readonly>
                </td>
                <td>
                    <input type="number" id="price" name="price" required>
                </td>
                <td>
                    <button class="add-to-cart-btn" id="submitBtn">Add to Cart</button>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Area to show the response after submission -->
    <div id="response"></div>

    <!-- Add jQuery (for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // JavaScript for handling the form submission via AJAX
        $('#submitBtn').click(function(event) {
            event.preventDefault(); // Prevent the default form behavior (no page reload)

            var itemName = $('#item_name').val(); // Get the predefined item name
            var price = $('#price').val(); // Get the value entered in the price field

            // Check if the price field is filled out
            if (price) {
                // AJAX request to submit the data to submit_item.php
                $.ajax({
                    url: 'submit_item.php',  // Path to the PHP script that will handle the submission
                    type: 'POST',
                    data: { item_name: itemName, price: price },  // Send item name and price to the PHP script
                    success: function(response) {
                        // Show the response from the PHP script (success/error message)
                        $('#response').html(response);
                    },
                    error: function() {
                        // Show an error message if the submission fails
                        $('#response').html('Error submitting form.');
                    }
                });
            } else {
                // Show an error message if no price is entered
                $('#response').html('Please enter a valid price.');
            }
        });
    </script>
    </main>
</body>
</html>