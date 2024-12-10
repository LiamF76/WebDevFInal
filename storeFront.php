<?php

session_start();
require_once 'auth.php';

// Check if user is logged in
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

$host = 'localhost'; 
$dbname = 'book_test'; 
$user = 'liam'; 
$pass = 'liam';
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
    $search_sql = 'SELECT stu_id, stu_name, stu_year, stu_GPA FROM student_records WHERE stu_name LIKE :search';
    $search_stmt = $pdo->prepare($search_sql);
    $search_stmt->execute(['search' => $search_term]);
    $search_results = $search_stmt->fetchAll();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['stu_name']) && isset($_POST['stu_name']) && isset($_POST['stu_GPA'])) {
        // Insert new entry
        $stu_name = htmlspecialchars($_POST['stu_name']);
        $stu_year = htmlspecialchars($_POST['stu_year']);
        $stu_GPA = htmlspecialchars($_POST['stu_GPA']);
        
        $insert_sql = 'INSERT INTO student_records (stu_name, stu_year, stu_GPA) VALUES (:stu_name, :stu_year, :stu_GPA)';
        $stmt_insert = $pdo->prepare($insert_sql);
        $stmt_insert->execute(['stu_name' => $stu_name, 'stu_year' => $stu_year, 'stu_GPA' => $stu_GPA]);
    } elseif (isset($_POST['delete_id'])) {
        // Delete an entry
        $delete_id = (int) $_POST['delete_id'];
        
        $delete_sql = 'DELETE FROM student_records WHERE stu_id = :stu_id';
        $stmt_delete = $pdo->prepare($delete_sql);
        $stmt_delete->execute(['stu_id' => $delete_id]);
    }
}

// Get all books for main table
$sql = 'SELECT stu_id, stu_name, stu_year, stu_GPA FROM student_records';
$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Concord University Student Record Database</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <h1 class="hero-title">Concord University Student Record Database</h1>
        <p class="hero-subtitle">"Come to Learn, Go to Serve"</p>
        
        <!-- Search moved to hero section -->
        <div class="hero-search">
            <h2>Search for a Student:</h2>
            <form action="" method="GET" class="search-form">
                <label for="search">Search by Name:</label>
                <input type="text" id="search" name="search" required>
                <input type="submit" value="Search">
            </form>
            
            <?php if (isset($_GET['search'])): ?>
                <div class="search-results">
                    <h3>Search Results</h3>
                    <?php if ($search_results && count($search_results) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Year</th>
                                    <th>GPA</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($search_results as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['stu_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['stu_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['stu_year']); ?></td>
                                    <td><?php echo htmlspecialchars($row['stu_GPA']); ?></td>
                                    <td>
                                        <form action="index5.php" method="post" style="display:inline;">
                                            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                            <input type="submit" value="Ban!">
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No students found matching your search.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Table section with container -->
    <div class="table-container">
        <h2>All Students in Database</h2>
        <table class="half-width-left-align">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Year</th>
                    <th>GPA</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['stu_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['stu_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['stu_year']); ?></td>
                    <td><?php echo htmlspecialchars($row['stu_GPA']); ?></td>
                    <td>
                        <form action="index5.php" method="post" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?php echo $row['stu_id']; ?>">
                            <input type="submit" value="Drop.">
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Form section with container -->
    <div class="form-container">
        <h2>Add a Student:</h2>
        <form action="index5.php" method="post">
            <label for="stu_name">Name:</label>
            <input type="text" id="stu_name" name="stu_name" required>
            <br><br>
            <label for="stu_year">Year:</label>
            <input type="text" id="stu_year" name="stu_year" required>
            <br><br>
            <label for="stu_GPA">GPA:</label>
            <input type="text" id="stu_GPA" name="stu_GPA" required>
            <br><br>
            <input type="submit" value="Add Student">
        </form>
    </div>
</body>
</html>