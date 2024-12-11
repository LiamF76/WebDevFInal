<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $itemId = $_POST['item_id'];

    // Generate temporary content
    $content = "This is a temporary file for item ID: $itemId.";

    // Set headers for download
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="item_' . $itemId . '.txt"');

    // Output content
    echo $content;
    exit;
} else {
    echo "Invalid request.";
}
?>