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

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $price = $_POST['price'];
    $itemName = "Preset Item Name"; // Example preset name

    if (!empty($price) && is_numeric($price) && $price > 0) {
        $stmt = $dsn->prepare("INSERT INTO store (name, price) VALUES (:name, :price)");
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

    <h2>Add Item to Store</h2>
        <form method="POST" action="storeFront.php">
            <div>
                <label for="price">Price (Gold):</label>
                <input type="number" id="price" name="price" min="1" required>
            </div>
            <button type="submit">Submit</button>
        </form>

    <!-- Table for submitting predefined item name and price -->
    <table border="1">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Description</th>
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
                    A fantastic map of Western Escarth, a continent ripe with adventure.
                    From the Dwarven Kingdom of Bourn Dorhul and its vast underground treasures,
                    to the beautiful fields and beaches of the Freehold, there is awlways somewhere to explore.
                    But be wary of the dark kingdom of Arkonia, ruled by the rebel King Ivar the Uncrowned
                    and his evil blade of the Abyss, Arkonil the Reckoner.
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
                    The quintessential desert fantasy world, Drok Shah is a land of mystery and danger.
                    It houses many locations to explore like the Blood Circle gladiator arena,
                    the deadly Green Triangle desert, and the beautiful Emerald Isles.
                    Players can enjoy an adventure through the schorching sands and explore underground
                    cave systems filled with lost histories and treasures.
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