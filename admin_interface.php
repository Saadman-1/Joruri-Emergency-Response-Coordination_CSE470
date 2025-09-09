<?php include 'darkmode_auto.php'; ?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['adminname'])) {
    header('Location: admin.php');
    exit();
}

$admin_name = $_SESSION['adminname'];
?>
<?php
// Inventory management logic
$inventory_message = '';
$inventory_data = [];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'database470');
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// Handle Add Item
if (isset($_POST['add_item'])) {
    $name = $conn->real_escape_string($_POST['item_name']);
    $quantity = intval($_POST['item_quantity']);
    if ($name && $quantity > 0) {
        // Check if item exists
        $check = $conn->query("SELECT * FROM inventory WHERE name='$name'");
        if ($check->num_rows > 0) {
            $conn->query("UPDATE inventory SET quantity = quantity + $quantity WHERE name='$name'");
            $inventory_message = "Added $quantity to existing item '$name'.";
        } else {
            $conn->query("INSERT INTO inventory (name, quantity) VALUES ('$name', $quantity)");
            $inventory_message = "Added new item '$name' with quantity $quantity.";
        }
    } else {
        $inventory_message = "Please enter a valid name and quantity.";
    }
}

// Handle Delete Item
if (isset($_POST['delete_item'])) {
    $name = $conn->real_escape_string($_POST['item_name']);
    $quantity = intval($_POST['item_quantity']);
    if ($name && $quantity > 0) {
        // Check if item exists and quantity
        $check = $conn->query("SELECT quantity FROM inventory WHERE name='$name'");
        if ($check->num_rows > 0) {
            $row = $check->fetch_assoc();
            if ($row['quantity'] >= $quantity) {
                $conn->query("UPDATE inventory SET quantity = quantity - $quantity WHERE name='$name'");
                // Optionally delete if quantity becomes 0
                $conn->query("DELETE FROM inventory WHERE name='$name' AND quantity <= 0");
                $inventory_message = "Deleted $quantity from item '$name'.";
            } else {
                $inventory_message = "Not enough quantity to delete. Current: " . $row['quantity'];
            }
        } else {
            $inventory_message = "Item '$name' not found.";
        }
    } else {
        $inventory_message = "Please enter a valid name and quantity.";
    }
}

// Handle See Inventory and Search
if (isset($_POST['see_inventory']) || isset($_POST['search_inventory'])) {
    $search_query = '';
    if (isset($_POST['search_inventory'])) {
        $search_query = $conn->real_escape_string($_POST['search_name']);
        $result = $conn->query("SELECT * FROM inventory WHERE name LIKE '%$search_query%'");
    } else {
        $result = $conn->query("SELECT * FROM inventory");
    }
    while ($row = $result->fetch_assoc()) {
        $inventory_data[] = $row;
    }
}

$conn->close();
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
        </div>
        <div style="text-align:center;margin-bottom:30px;">
            <span style="display:inline-block;padding:18px 32px;background:var(--accent);color:#fff;font-size:1.4em;font-weight:bold;border-radius:10px;box-shadow:0 2px 12px var(--shadow);margin-top:10px;">Welcome, <?php echo htmlspecialchars($admin_name); ?>!</span>
        </div>
        
        <div class="dashboard-grid">
            <!-- ...existing cards... -->
            <div class="dashboard-card">
                <div class="card-title">� Inventory Management</div>
                <div class="card-content">
                    <form method="post" style="margin-bottom:10px;display:inline-block;">
                        <button type="submit" name="see_inventory" class="btn">See Inventory</button>
                    </form>
                    <form method="post" style="margin-bottom:10px;display:inline-block;">
                        <input type="text" name="search_name" placeholder="Search by Name">
                        <button type="submit" name="search_inventory" class="btn">Search</button>
                    </form>
                    <form method="post" style="margin-bottom:10px;">
                        <input type="text" name="item_name" placeholder="Item Name" required>
                        <input type="number" name="item_quantity" placeholder="Quantity" min="1" required>
                        <button type="submit" name="add_item" class="btn">Add Item</button>
                    </form>
                    <form method="post">
                        <input type="text" name="item_name" placeholder="Item Name" required>
                        <input type="number" name="item_quantity" placeholder="Quantity" min="1" required>
                        <button type="submit" name="delete_item" class="btn">Delete Item</button>
                    </form>
                    <?php if ($inventory_message) { echo '<div style="color:var(--accent);margin-top:10px;font-weight:bold;">' . $inventory_message . '</div>'; } ?>
                    <?php if (!empty($inventory_data)) { ?>
                        <div style="margin-top:15px;max-height:350px;overflow-y:auto;">
                            <table border="1" cellpadding="8" style="width:100%;background:var(--bg-primary);color:var(--text-primary);border-radius:8px;">
                                <tr style="background:var(--bg-secondary);color:var(--accent);font-weight:bold;">
                                    <th>Name</th>
                                    <th>Quantity</th>
                                </tr>
                                <?php foreach ($inventory_data as $item) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    <?php } ?>
                </div>
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
