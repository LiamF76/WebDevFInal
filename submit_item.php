<?php
// submit_item.php - Handles the form data submission

// Check if the form was submitted via POST and contains the 'price' field
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['price'])) {
    // Get the 'price' value from the form submission
    $price = $_POST['price'];

    // Check if the 'price' is a valid number (you can customize this as needed)
    if (is_numeric($price) && $price > 0) {
        // Here you can insert the price into the database or process it further.
        // For now, we'll just return a success message.
        echo "Price submitted successfully: " . htmlspecialchars($price);
    } else {
        // Return an error if the price is not valid
        echo "Error: Please enter a valid price.";
    }
} else {
    // Return an error if 'price' is not set or if the form was not submitted via POST
    echo "Error: Price is required.";
}
?>