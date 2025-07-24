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
<html>
<head>
    <title>Track Your Need - Joruri</title>
    <meta http-equiv="refresh" content="10">
    <style>
        body {
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .container {
            background: #fff;
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h2 {
            margin-bottom: 30px;
            font-size: 2em;
            color: #333;
        }
        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-size: 1.1em;
            color: #333;
        }
        input[type="text"], input[type="tel"] {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 8px;
        }
        .btn {
            width: 100%;
            padding: 14px;
            font-size: 1.2em;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .info {
            margin-top: 24px;
            font-size: 1.1em;
            color: #222;
            background: #e9f7ef;
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
