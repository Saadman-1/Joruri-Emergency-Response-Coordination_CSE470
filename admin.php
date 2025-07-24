<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Database connection (update username/password if needed)
$conn = new mysqli("localhost", "root", "", "database470");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $password = $_POST['password'] ?? '';

    // Prepared statement for security
    $stmt = $conn->prepare("SELECT * FROM login WHERE name=? AND password=?");
    $stmt->bind_param("ss", $name, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows == 1) {
        $_SESSION['adminname'] = $name;
        header("Location: admin_interface.php");
        exit();
    } else {
        $error = "Incorrect name or password";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body {
            height: 100vh;
            background-color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .box {
            background-color: #f8f9fa;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        h2 {
            font-size: 2em;
            margin-bottom: 30px;
        }
        label {
            font-size: 1.2em;
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"], input[type="password"] {
            font-size: 1.1em;
            padding: 10px;
            width: 90%;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        input[type="submit"] {
            font-size: 1.1em;
            padding: 10px 30px;
            border: none;
            border-radius: 6px;
            background: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background 0.2s;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
        .error-message {
            color: red;
            font-size: 1.1em;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>Admin Login</h2>
        <form method="post" action="admin.php">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required><br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br>
            <input type="submit" value="Login">
        </form>
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>