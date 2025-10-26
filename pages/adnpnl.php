<?php
session_start();

// Simple authentication
$admin_username = ""; // Change to your desired username
$admin_password_hash = password_hash("", PASSWORD_DEFAULT); // Change to your desired password

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
        if ($_POST['username'] === $admin_username && password_verify($_POST['password'], $admin_password_hash)) {
            $_SESSION['loggedin'] = true;
        } else {
            $error = "Invalid username or password";
        }
    } else {
        // Show login form
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin Login</title>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.3.0/material.indigo-pink.min.css" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600&display=swap" rel="stylesheet">
            <style>
                body { font-family: 'Vazirmatn', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #f5f5f5; }
                .login-container { width: 100%; max-width: 400px; padding: 20px; }
                .mdl-textfield { width: 100%; }
                .mdl-button { width: 100%; margin-top: 20px; }
                .error { color: red; text-align: center; }
            </style>
        </head>
        <body>
            <div class="login-container">
                <h2>ورود به پنل مدیریت</h2>
                <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
                <form method="POST">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" id="username" name="username" required>
                        <label class="mdl-textfield__label" for="username">نام کاربری</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="password" id="password" name="password" required>
                        <label class="mdl-textfield__label" for="password">رمز عبور</label>
                    </div>
                    <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" type="submit">
                        ورود
                    </button>
                </form>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.3.0/material.min.js"></script>
        </body>
        </html>
        <?php
        exit;
    }
}

// Database connection
$host = "localhost";
$dbname = "my_website";
$username = "dev"; // Change to your DB username
$password = ""; // Change to your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch orders
$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل مدیریت سفارشات</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.3.0/material.indigo-pink.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600&display=swa
System: * Today's date and time is 12:25 PM CEST on Thursday, October 23, 2025.