<?php include 'darkmode_auto.php'; ?>
<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$need = "";
$info = null;
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';

    $conn = new mysqli("localhost", "root", "", "database470");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT need, calamity, location, `blood-group`, r-date FROM victim WHERE name=? AND phone=? ORDER BY r-date DESC LIMIT 1");
    $stmt->bind_param("ss", $name, $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $info = $result->fetch_assoc();
        $need = $info['need'];
    } else {
        $message = "No record found for the provided name and phone number.";
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Track Your Need - Joruri</title>
    <meta http-equiv="refresh" content="10">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-primary);
            font-family: Arial, sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
            color: var(--text-primary);
        }
        .container {
            background: var(--bg-secondary);
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 4px 24px var(--shadow);
            text-align: center;
            max-width: 400px;
            width: 100%;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }
        h2 {
            margin-bottom: 30px;
            font-size: 2em;
            color: var(--text-primary);
        }
        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-size: 1.1em;
            color: var(--text-secondary);
        }
        input[type="text"], input[type="tel"] {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            border: 1px solid var(--border);
            border-radius: 6px;
            margin-bottom: 8px;
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus, input[type="tel"]:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(77, 171, 247, 0.1);
        }
        .btn {
            width: 100%;
            padding: 14px;
            font-size: 1.2em;
            background-color: var(--accent);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
        }
        .info {
            margin-top: 24px;
            font-size: 1.1em;
            color: var(--text-primary);
            background: var(--bg-primary);
            border-radius: 8px;
            padding: 18px;
        }
        .message {
            margin-top: 18px;
            font-size: 1.1em;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Track Your Need</h2>
        <form method="post" action="track.php">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <button class="btn" type="submit">Track</button>
        </form>
        <?php if ($info): ?>
            <div class="info">
                <strong>Calamity:</strong> <?php echo htmlspecialchars($info['calamity']); ?><br>
                <strong>Location:</strong> <?php echo htmlspecialchars($info['location']); ?><br>
                <strong>Blood Group:</strong> <?php echo htmlspecialchars($info['blood-group']); ?><br>
                <strong>Reported Date:</strong> <?php echo htmlspecialchars($info['r-date']); ?><br>
                <strong>Current Need:</strong> <?php echo htmlspecialchars($info['need']); ?>
            </div>
        <?php elseif (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
