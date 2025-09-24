<?php
// db.php
// Database connection using PDO for security and flexibility.
 
$host = 'localhost'; // Change if not localhost
$dbname = 'dbbhd5nwnucgyi';
$dbuser = 'um4u5gpwc3dwc';
$dbpass = 'neqhgxo10ioe';
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("DB Connection Error: " . $e->getMessage());
    http_response_code(500);
    die("Database connection failed. Please check your configuration.");
}
 
// Function to get user ID from session
function getCurrentUserId() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}
 
// Function to require login
function requireLogin() {
    if (!getCurrentUserId()) {
        echo "<script>window.location.href = 'login.php';</script>";
        exit;
    }
}
?>
 
