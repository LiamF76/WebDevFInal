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

// Fetch items as an associative array
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <!-- Hero Section -->
    <div class="hero">
        <h1 class="hero-h1">Daraan Games Storefront</h1>
        <p class="hero-h2">
            "I gave the kingdom everything I had, and in their stories they shall return the favor.
            They will remember my name, as the one who wore the crown, not carried the sword.
            Lazarus of Praetoria."
        </p>

    <!-- Table section with container -->
        <h2>All Currently Available Products</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($items) > 0): ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                            <td>
                                <form action="download.php" method="post">
                                    <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">
                                    <button type="submit" class="download-button">Download</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No items found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <footer>
        <p>&copy; 2024 My Store. All rights reserved.</p>
        <div style="text-align: center; margin-top: 20px;">
            <a href="admin_page.php" class="button">Go to Admin Page</a>
        </div>  
    </footer>
</body>
</html>