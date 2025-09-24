<?php
// index.php - Homepage explaining the platform's features.
// Includes links to signup/login. No auth required.
 
include 'db.php'; // For session start if needed, but not required here.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickBooks Clone - Manage Your Finances</title>
    <style>
        /* Embedded CSS: Modern, responsive, professional design inspired by QuickBooks. Clean blues, whites, shadows for depth. */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        header { background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); padding: 1rem; text-align: center; }
        h1 { color: white; font-size: 2.5rem; margin-bottom: 0.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.3); }
        nav { margin-top: 1rem; }
        nav button { background: white; color: #667eea; border: none; padding: 0.8rem 1.5rem; margin: 0 0.5rem; border-radius: 25px; cursor: pointer; font-size: 1rem; transition: all 0.3s ease; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        nav button:hover { transform: translateY(-2px); box-shadow: 0 6px 12px rgba(0,0,0,0.15); background: #f0f0f0; }
        main { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .features { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 2rem; }
        .feature-card { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); transition: transform 0.3s ease; }
        .feature-card:hover { transform: translateY(-5px); }
        .feature-card h2 { color: #667eea; margin-bottom: 1rem; font-size: 1.5rem; }
        .feature-card p { color: #666; }
        .feature-icon { font-size: 3rem; color: #667eea; margin-bottom: 1rem; }
        footer { text-align: center; padding: 2rem; color: white; background: rgba(0,0,0,0.2); margin-top: 3rem; }
        @media (max-width: 768px) { h1 { font-size: 2rem; } .features { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <header>
        <h1>QuickBooks Clone</h1>
        <p>Your All-in-One Financial Management Platform</p>
        <nav>
            <button onclick="window.location.href='signup.php'">Sign Up</button>
            <button onclick="window.location.href='login.php'">Login</button>
        </nav>
    </header>
    <main>
        <section class="features">
            <div class="feature-card">
                <div class="feature-icon">💳</div>
                <h2>Invoice Management</h2>
                <p>Generate, send, and track professional invoices with PDF downloads and email integration.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h2>Expense & Income Tracking</h2>
                <p>Log expenses and incomes, categorize transactions, and maintain a clear financial overview.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📈</div>
                <h2>Financial Reports</h2>
                <p>Get monthly/yearly summaries and graphical insights into your income vs. expenses.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h2>Secure Authentication</h2>
                <p>Sign up, log in, and manage your profile with industry-standard security.</p>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 QuickBooks Clone. Built with PHP, HTML, CSS & JS.</p>
    </footer>
    <script>
        // Embedded JS: Minimal, for smooth interactions if needed.
        console.log('Welcome to QuickBooks Clone!');
    </script>
</body>
</html>
