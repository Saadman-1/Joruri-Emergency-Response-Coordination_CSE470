<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$message = "";
$login_message = "";

// Registration
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {
    $name = $_POST['name'] ?? '';
    $location = $_POST['location'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $blood = $_POST['blood'] ?? '';
    $password = $_POST['password'] ?? '';
    $conn = new mysqli("localhost", "root", "", "database470");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("INSERT INTO volunteer (name, location, `phone-no`, `blood-group`, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $location, $phone, $blood, $password);
    if ($stmt->execute()) {
        $message = "Thank you for registering as a volunteer!";
    } else {
        $message = "There was an error. Please try again.";
    }
    $stmt->close();
    $conn->close();
}

// Login
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    $login_name = $_POST['login_name'] ?? '';
    $login_password = $_POST['login_password'] ?? '';
    $conn = new mysqli("localhost", "root", "", "database470");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("SELECT * FROM volunteer WHERE name=? AND password=?");
    $stmt->bind_param("ss", $login_name, $login_password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        // Successful login
        session_start();
        $_SESSION['volunteer_name'] = $login_name;
        header("Location: task.php");
        exit();
    } else {
        $login_message = "Incorrect name or password.";
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Volunteer Registration & Login</title>
    <style>
        body {
            background: #f4f4f4;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
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
        input[type="text"], input[type="tel"], input[type="password"] {
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
        .message, .login-message {
            margin-top: 18px;
            font-size: 1.1em;
            color: green;
            text-align: center;
        }
        .login-message {
            color: red;
        }
        .divider {
            margin: 30px 0 20px 0;
            border-bottom: 1px solid #eee;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Volunteer Registration</h2>
    <form method="post" action="volunteer.php">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="location">Current Address</label>
            <input type="text" id="location" name="location" required>
        </div>
        <div class="form-group">
            <label for="phone">Mobile No.</label>
            <input type="tel" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="blood">Blood Group</label>
            <input type="text" id="blood" name="blood" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button class="btn" type="submit" name="register">Register</button>
    </form>
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <div class="divider"></div>
    <h2>Volunteer Login</h2>
    <form method="post" action="volunteer.php">
        <div class="form-group">
            <label for="login_name">Name</label>
            <input type="text" id="login_name" name="login_name" required>
        </div>
        <div class="form-group">
            <label for="login_password">Password</label>
            <input type="password" id="login_password" name="login_password" required>
        </div>
        <button class="btn" type="submit" name="login">Login</button>
    </form>
    <?php if (!empty($login_message)): ?>
        <div class="login-message"><?php echo htmlspecialchars($login_message); ?></div>
    <?php endif; ?>
</div>
</body>
</html>
