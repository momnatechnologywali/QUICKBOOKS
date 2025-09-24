<?php
// login.php - User login form.
// Processes form, verifies password, sets session, JS redirect to dashboard.
 
include 'db.php';
if (getCurrentUserId()) {
    echo "<script>window.location.href = 'dashboard.php';</script>";
    exit;
}
 
$message = '';
if ($_POST) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
 
    if (empty($email) || empty($password)) {
        $message = 'All fields are required.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                echo "<script>window.location.href = 'dashboard.php';</script>";
                exit;
            } else {
                $message = 'Invalid email or password.';
            }
        } catch (PDOException $e) {
            $message = 'Login failed: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - QuickBooks Clone</title>
    <style>
        /* Embedded CSS: Same style as signup for consistency. */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .form-container { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #667eea; margin-bottom: 1.5rem; }
        input { width: 100%; padding: 0.8rem; margin-bottom: 1rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; }
        button { width: 100%; padding: 0.8rem; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 1rem; transition: background 0.3s; }
        button:hover { background: #5a67d8; }
        .message { text-align: center; margin-bottom: 1rem; color: #e53e3e; }
        .link { text-align: center; margin-top: 1rem; }
        .link a { color: #667eea; text-decoration: none; }
        @media (max-width: 480px) { .form-container { margin: 1rem; padding: 1.5rem; } }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="link">
            <a href="#" onclick="window.location.href='signup.php'">Don't have an account? Sign Up</a>
        </div>
        <div class="link">
            <a href="#" onclick="window.location.href='index.php'">Back to Home</a>
        </div>
    </div>
    <script>
        // Embedded JS: Focus on email field.
        document.querySelector('input[type="email"]').focus();
    </script>
</body>
</html>
