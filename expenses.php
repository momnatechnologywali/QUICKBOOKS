<?php
// expenses.php - Expense and income tracking.
// Handles adding transactions.
 
include 'db.php';
requireLogin();
$user_id = getCurrentUserId();
 
// Handle add transaction
if ($_POST && isset($_POST['add_transaction'])) {
    $type = $_POST['type'];
    $amount = floatval($_POST['amount']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $date = $_POST['date'];
 
    if ($amount > 0 && !empty($category)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, category, description, date) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $type, $amount, $category, $description, $date]);
            echo "<script>alert('Transaction added!'); window.location.reload();</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('Invalid input.');</script>";
    }
}
 
// Fetch all transactions
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses & Income - QuickBooks Clone</title>
    <style>
        /* Embedded CSS: Similar to dashboard. */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; color: #333; }
        header { background: #667eea; color: white; padding: 1rem; display: flex; justify-content: space-between; }
        h1 { font-size: 1.5rem; }
        nav button { background: rgba(255,255,255,0.2); color: white; border: 1px solid white; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer; margin-left: 0.5rem; }
        main { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .form-section { background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 2rem; }
        input, select, textarea { width: 100%; padding: 0.5rem; margin-bottom: 0.5rem; border: 1px solid #ddd; border-radius: 5px; }
        button { padding: 0.5rem 1rem; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .income-btn { background: #28a745; } .expense-btn { background: #dc3545; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 0.8rem; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        .income { color: #28a745; } .expense { color: #dc3545; }
        @media (max-width: 768px) { table { font-size: 0.9rem; } }
    </style>
</head>
<body>
    <header>
        <h1>Expenses & Income</h1>
        <nav>
            <button onclick="window.location.href='dashboard.php'">Dashboard</button>
            <button onclick="window.location.href='invoices.php'">Invoices</button>
            <button onclick="window.location.href='reports.php'">Reports</button>
            <button onclick="window.location.href='profile.php'">Profile</button>
            <button onclick="if(confirm('Logout?')) window.location.href='logout.php'">Logout</button>
        </nav>
    </header>
    <main>
        <div class="form-section">
            <h2>Add Transaction</h2>
            <form method="POST">
                <select name="type" required>
                    <option value="">Select Type</option>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
                <input type="number" name="amount" placeholder="Amount" min="0" step="0.01" required>
                <input type="text" name="category" placeholder="Category" required>
                <textarea name="description" placeholder="Description"></textarea>
                <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                <button type="submit" name="add_transaction" class="income-btn">Add Income</button>
                <button type="submit" name="add_transaction" class="expense-btn">Add Expense</button>
            </form>
        </div>
        <div class="form-section">
            <h2>Transaction History</h2>
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
        // Embedded JS: Auto-submit based on type? Optional.
        document.querySelector('select[name="type"]').addEventListener('change', function() {
            const incomeBtn = document.querySelector('.income-btn');
            const expenseBtn = document.querySelector('.expense-btn');
            if (this.value === 'income') {
                incomeBtn.style.display = 'inline-block';
                expenseBtn.style.display = 'none';
            } else if (this.value === 'expense') {
                expenseBtn.style.display = 'inline-block';
                incomeBtn.style.display = 'none';
            } else {
                incomeBtn.style.display = 'inline-block';
                expenseBtn.style.display = 'inline-block';
            }
        });
    </script>
</body>
</html>
