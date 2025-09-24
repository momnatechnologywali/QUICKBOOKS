<?php
// dashboard.php - Main dashboard with financial summaries and transaction history.
// Requires login. Fetches data from DB.
 
include 'db.php';
requireLogin();
$user_id = getCurrentUserId();
 
// Fetch summaries
$stmt = $pdo->prepare("
    SELECT 
        SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
        SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense,
        (SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) - SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END)) as balance
    FROM transactions WHERE user_id = ?
");
$stmt->execute([$user_id]);
$summary = $stmt->fetch();
 
$total_income = $summary['total_income'] ?? 0;
$total_expense = $summary['total_expense'] ?? 0;
$balance = $summary['balance'] ?? 0;
 
// Recent transactions (last 10)
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC, created_at DESC LIMIT 10");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - QuickBooks Clone</title>
    <style>
        /* Embedded CSS: Dashboard layout with cards, responsive grid. Modern charts simulation with CSS. */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; color: #333; }
        header { background: #667eea; color: white; padding: 1rem; display: flex; justify-content: space-between; align-items: center; }
        h1 { font-size: 1.5rem; }
        nav { display: flex; gap: 1rem; }
        nav button { background: rgba(255,255,255,0.2); color: white; border: 1px solid white; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
        nav button:hover { background: white; color: #667eea; }
        main { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .card { background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-align: center; }
        .card h3 { color: #667eea; margin-bottom: 0.5rem; }
        .card .value { font-size: 2rem; font-weight: bold; color: #28a745; }
        .negative { color: #dc3545; }
        .transactions { background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.8rem; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        .income { color: #28a745; }
        .expense { color: #dc3545; }
        .logout { background: #dc3545; }
        @media (max-width: 768px) { .summary-grid { grid-template-columns: 1fr; } nav { flex-direction: column; gap: 0.5rem; } }
    </style>
</head>
<body>
    <header>
        <h1>Dashboard</h1>
        <nav>
            <button onclick="window.location.href='invoices.php'">Invoices</button>
            <button onclick="window.location.href='expenses.php'">Expenses</button>
            <button onclick="window.location.href='reports.php'">Reports</button>
            <button onclick="window.location.href='profile.php'">Profile</button>
            <button class="logout" onclick="if(confirm('Logout?')) window.location.href='logout.php'">Logout</button>
        </nav>
    </header>
    <main>
        <div class="summary-grid">
            <div class="card">
                <h3>Total Income</h3>
                <div class="value income">$<?php echo number_format($total_income, 2); ?></div>
            </div>
            <div class="card">
                <h3>Total Expenses</h3>
                <div class="value expense">-$<?php echo number_format($total_expense, 2); ?></div>
            </div>
            <div class="card">
                <h3>Balance</h3>
                <div class="value <?php echo $balance >= 0 ? 'income' : 'expense'; ?>">$<?php echo number_format($balance, 2); ?></div>
            </div>
        </div>
        <div class="transactions">
            <h2>Recent Transactions</h2>
            <table>
                <thead>
                    <tr><th>Date</th><th>Type</th><th>Category</th><th>Amount</th><th>Description</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $tx): ?>
                        <tr>
                            <td><?php echo date('Y-m-d', strtotime($tx['date'])); ?></td>
                            <td><?php echo ucfirst($tx['type']); ?></td>
                            <td><?php echo htmlspecialchars($tx['category']); ?></td>
                            <td class="<?php echo $tx['type']; ?>"><?php echo $tx['type'] == 'income' ? '+' : '-'; ?>$<?php echo number_format($tx['amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars($tx['description'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($transactions)): ?>
                        <tr><td colspan="5">No transactions yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
    <script>
        // Embedded JS: Dynamic updates if needed, but static for now.
        console.log('Dashboard loaded.');
    </script>
</body>
</html>
