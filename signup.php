<?php
// signup.php - User signup form.
// Processes form, hashes password, inserts to DB, then JS redirect to login.
 
include 'db.php';
requireLogin(); // No, not required here.
 
$message = '';
if ($_POST) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
 
    if (empty($username) || empty($email) || empty($password)) {
        $message = 'All fields are required.';
    } elseif (strlen($password) < 6) {
        $message = 'Password must be at least 6 characters.';
    } else {
        try {
            // Check if user exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $message = 'Username or email already exists.';
            } else {
                // Hash password
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $hashed]);
                $message = 'Signup successful! Please log in.';
                echo "<script>alert('$message'); window.location.href = 'login.php';</script>";
                exit;
            }
        } catch (PDOException $e) {
            $message = 'Signup failed: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - QuickBooks Clone</title>
    <style>
        /* Embedded CSS: Clean form design, centered, responsive. */
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
        <h2>Sign Up</h2>
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>">
            <input type="email" name="email" placeholder="Email" required value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <div class="link">
            <a href="#" onclick="window.location.href='login.php'">Already have an account? Login</a>
        </div>
        <div class="link">
            <a href="#" onclick="window.location.href='index.php'">Back to Home</a>
        </div>
    </div>
    <script>
        // Embedded JS: Form validation.
        document.querySelector('form').addEventListener('submit', function(e) {
            const pass = document.querySelector('input[type="password"]').value;
            if (pass.length < 6) {
                alert('Password must be at least 6 characters.');
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
