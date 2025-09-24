<?php
// reports.php - Basic financial reports: summaries and graphical overview.
// Uses Chart.js embedded via CDN for graphs. Fetches monthly/yearly data.
 
include 'db.php';
requireLogin();
$user_id = getCurrentUserId();
 
// Monthly summary (current year)
$current_year = date('Y');
$stmt = $pdo->prepare("
    SELECT MONTH(date) as month, 
           SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
           SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
    FROM transactions WHERE user_id = ? AND YEAR(date) = ? GROUP BY MONTH(date) ORDER BY month
");
$stmt->execute([$user_id, $current_year]);
$monthly_data = $stmt->fetchAll();
 
// Yearly summary (last 5 years)
$stmt = $pdo->prepare("
    SELECT YEAR(date) as year,
           SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
           SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
    FROM transactions WHERE user_id = ? AND YEAR(date) >= YEAR(DATE_SUB(NOW(), INTERVAL 5 YEAR)) GROUP BY year ORDER BY year
");
$stmt->execute([$user_id]);
$yearly_data = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - QuickBooks Clone</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Embedded CSS: Charts container responsive. */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f8f9fa; color: #333; }
        header { background: #667eea; color: white; padding: 1rem; display: flex; justify-content: space-between; }
        h1 { font-size: 1.5rem; }
        nav button { background: rgba(255,255,255,0.2); color: white; border: 1px solid white; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer; margin-left: 0.5rem; }
        main { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .report-section { background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 2rem; }
        .summary-table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 0.8rem; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        canvas { max-height: 400px; }
        @media (max-width: 768px) { canvas { height: 300px !important; } }
    </style>
</head>
<body>
    <header>
        <h1>Financial Reports</h1>
        <nav>
            <button onclick="window.location.href='dashboard.php'">Dashboard</button>
            <button onclick="window.location.href='invoices.php'">Invoices</button>
            <button onclick="window.location.href='expenses.php'">Expenses</button>
            <button onclick="window.location.href='profile.php'">Profile</button>
            <button onclick="if(confirm('Logout?')) window.location.href='logout.php'">Logout</button>
        </nav>
    </header>
    <main>
        <div class="report-section">
            <h2>Monthly Summary (<?php echo $current_year; ?>)</h2>
            <canvas id="monthlyChart"></canvas>
            <table class="summary-table">
                <thead><tr><th>Month</th><th>Income</th><th>Expense</th><th>Net</th></tr></thead>
                <tbody>
                    <?php foreach ($monthly_data as $month): ?>
                        <?php $net = ($month['income'] ?? 0) - ($month['expense'] ?? 0); ?>
                        <tr>
                            <td><?php echo date('M', mktime(0,0,0,$month['month'])); ?></td>
                            <td>$<?php echo number_format($month['income'] ?? 0, 2); ?></td>
                            <td>$<?php echo number_format($month['expense'] ?? 0, 2); ?></td>
                            <td>$<?php echo number_format($net, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="report-section">
            <h2>Yearly Summary (Last 5 Years)</h2>
            <canvas id="yearlyChart"></canvas>
            <table class="summary-table">
                <thead><tr><th>Year</th><th>Income</th><th>Expense</th><th>Net</th></tr></thead>
                <tbody>
                    <?php foreach ($yearly_data as $year): ?>
                        <?php $net = ($year['income'] ?? 0) - ($year['expense'] ?? 0); ?>
                        <tr>
                            <td><?php echo $year['year']; ?></td>
                            <td>$<?php echo number_format($year['income'] ?? 0, 2); ?></td>
                            <td>$<?php echo number_format($year['expense'] ?? 0, 2); ?></td>
                            <td>$<?php echo number_format($net, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <script>
        // Embedded JS: Render charts with Chart.js.
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: [<?php echo implode(',', array_map(function($m) { return '"' . date('M', mktime(0,0,0,$m['month'])) . '"'; }, $monthly_data)); ?>],
                datasets: [{
                    label: 'Income',
                    data: [<?php echo implode(',', array_column($monthly_data, 'income')); ?>],
                    backgroundColor: '#28a745'
                }, {
                    label: 'Expense',
                    data: [<?php echo implode(',', array_column($monthly_data, 'expense')); ?>],
                    backgroundColor: '#dc3545'
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
 
        const yearlyCtx = document.getElementById('yearlyChart').getContext('2d');
        new Chart(yearlyCtx, {
            type: 'line',
            data: {
                labels: [<?php echo implode(',', array_map(function($y) { return '"' . $y['year'] . '"'; }, $yearly_data)); ?>],
                datasets: [{
                    label: 'Income',
                    data: [<?php echo implode(',', array_column($yearly_data, 'income')); ?>],
                    borderColor: '#28a745'
                }, {
                    label: 'Expense',
                    data: [<?php echo implode(',', array_column($yearly_data, 'expense')); ?>],
                    borderColor: '#dc3545'
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
    </script>
</body>
</html>
