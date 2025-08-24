<?php include 'darkmode_auto.php'; ?>
<?php
session_start();
if (!isset($_SESSION['adminname'])) {
    header('Location: admin.php');
    exit();
}

$admin_name = $_SESSION['adminname'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Interface - Joruri</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: var(--bg-primary);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            transition: background-color 0.3s ease, color 0.3s ease;
            color: var(--text-primary);
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            background: var(--bg-secondary);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 24px var(--shadow);
            text-align: center;
            margin-bottom: 30px;
            border: 1px solid var(--border);
        }
        h1 {
            color: var(--text-primary);
            margin: 0 0 10px 0;
        }
        .admin-info {
            color: var(--text-secondary);
            font-size: 1.2em;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .dashboard-card {
            background: var(--bg-secondary);
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 24px var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px var(--shadow);
        }
        .card-title {
            color: var(--accent);
            font-size: 1.5em;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .card-content {
            color: var(--text-secondary);
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            margin: 10px 5px;
            padding: 12px 24px;
            background-color: var(--accent);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: bold;
        }
        .btn:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
        }
        .logout-btn {
            background-color: #dc3545;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚨 Joruri Admin Dashboard</h1>
            <div class="admin-info">Welcome, <?php echo htmlspecialchars($admin_name); ?>!</div>
        </div>
        
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="card-title">📊 System Overview</div>
                <div class="card-content">
                    Monitor emergency response coordination, track victims and volunteers, and manage system operations.
                </div>
                <a href="#" class="btn">View Statistics</a>
            </div>
            
            <div class="dashboard-card">
                <div class="card-title">👥 User Management</div>
                <div class="card-content">
                    Manage volunteer accounts, review victim registrations, and oversee user permissions.
                </div>
                <a href="#" class="btn">Manage Users</a>
            </div>
            
            <div class="dashboard-card">
                <div class="card-title">🚨 Emergency Response</div>
                <div class="card-content">
                    Coordinate disaster response efforts, assign volunteers, and track emergency situations.
                </div>
                <a href="#" class="btn">Emergency Center</a>
            </div>
            
            <div class="dashboard-card">
                <div class="card-title">📋 Reports & Analytics</div>
                <div class="card-content">
                    Generate reports, analyze response times, and review system performance metrics.
                </div>
                <a href="#" class="btn">View Reports</a>
            </div>
        </div>
        
        <div style="text-align: center;">
            <a href="admin.php" class="btn">Back to Admin Login</a>
            <a href="login.php" class="btn">Main Menu</a>
            <a href="logout.php" class="btn logout-btn">Logout</a>
        </div>
    </div>
</body>
</html>
