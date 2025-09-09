<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include dark mode functionality
include 'darkmode_include.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
<html lang="en">
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            height: 100vh;
            background-color: var(--bg-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease, color 0.3s ease;
            color: var(--text-primary);
        }
        .box {
            background-color: var(--bg-secondary);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px var(--shadow);
            text-align: center;
            width: 100%;
            max-width: 400px;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }
        h2 {
            font-size: 2em;
            margin-bottom: 30px;
            color: var(--text-primary);
        }
        label {
            font-size: 1.2em;
            display: block;
            margin-bottom: 10px;
            color: var(--text-secondary);
        }
        input[type="text"], input[type="password"] {
            font-size: 1.1em;
            padding: 10px;
            width: 90%;
            margin-bottom: 20px;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(77, 171, 247, 0.1);
        }
        input[type="submit"] {
            font-size: 1.1em;
            padding: 10px 30px;
            border: none;
            border-radius: 6px;
            background: var(--accent);
            color: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        input[type="submit"]:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
        }
        .error-message {
            color: red;
            font-size: 1.1em;
            margin-top: 15px;
        }
    </style>
</head>
<body data-theme="<?php echo getThemeAttribute(); ?>">
    
    <!-- Dark Mode Toggle Button -->
    <?php echo getThemeToggleButton(); ?>

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

    <script>
        // Add smooth theme switching animation
        document.addEventListener('DOMContentLoaded', function() {
            const body = document.body;
            
            // Add transition class after page load for smooth initial theme
            setTimeout(() => {
                body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
            }, 100);
        });
    </script>
</body>
</html>