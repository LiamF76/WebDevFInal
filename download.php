<?php
$dsn = 'mysql:host=localhost;dbname=finalproject;charset=utf8';
$username = 'root';
$password = 'mysql';

try {
    // Create PDO connection
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
        $item_id = $_POST['item_id'];

        // Fetch the file path from the database
        $stmt = $pdo->prepare("SELECT file_path FROM store WHERE item_id = :item_id");
        $stmt->execute([':item_id' => $item_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && file_exists($result['file_path'])) {
            $filePath = $result['file_path'];

            // Send file to the user
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            echo "File not found.";
        }
    } else {
        echo "Invalid request.";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>