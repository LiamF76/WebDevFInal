<?php
// login.php - Login page
session_start();
require_once 'config.php';
require_once 'auth.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        if (login_user($pdo, $username, $password)) {
            header('Location: storeFront.php');
            exit;
        } else {
            $error_message = 'Invalid username or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Daraan Games Store</title>

    <link rel="stylesheet" href="finalStyles.css">
    <link rel="icon" href="favicon.ico.png">
</head>

<body style ="background-color: #3b3b3b">
<header>
        <div class="logo">
            <h1>My Store</h1>
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
        <h1>Login</h1>
        <?php if ($error_message): ?>
            <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" class="auth-form">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="login">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>