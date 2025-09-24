<?php
// profile.php - Profile management: view/edit user info.
// For simplicity, view only; extend for edit.
 
include 'db.php';
requireLogin();
$user_id = getCurrentUserId();
 
$stmt = $pdo->prepare("SELECT username, email, created_at FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - QuickBooks Clone</title>
    <style>
        /* Embedded CSS: Simple profile view. */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; color: #333; }
        header { background: #667eea; color: white; padding: 1rem; display: flex; justify-content: space-between; }
        h1 { font-size: 1.5rem; }
        nav button { background: rgba(255,255,255,0.2); color: white; border: 1px solid white; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer; margin-left: 0.5rem; }
        main { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .profile-card { background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); max-width: 400px; }
        .profile-info { margin-bottom: 1rem; }
        .profile-info strong { color: #667eea; }
        @media (max-width: 768px) { .profile-card { margin: 0 1rem; } }
    </style>
</head>
<body>
    <header>
        <h1>Profile</h1>
        <nav>
            <button onclick="window.location.href='dashboard.php'">Dashboard</button>
            <button onclick="window.location.href='invoices.php'">Invoices</button>
            <button onclick="window.location.href='expenses.php'">Expenses</button>
            <button onclick="window.location.href='reports.php'">Reports</button>
            <button onclick="if(confirm('Logout?')) window.location.href='logout.php'">Logout</button>
        </nav>
    </header>
    <main>
        <div class="profile-card">
            <h2>Your Account</h2>
            <div class="profile-info"><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></div>
            <div class="profile-info"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></div>
            <div class="profile-info"><strong>Member Since:</strong> <?php echo date('Y-m-d', strtotime($user['created_at'])); ?></div>
            <!-- Add edit form here if needed -->
        </div>
    </main>
    <script>
        // Embedded JS: None needed.
    </script>
</body>
</html>
