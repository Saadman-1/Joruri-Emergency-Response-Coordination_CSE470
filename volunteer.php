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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['volunteer_name'] = $login_name;
        ob_clean();
        header("Location: track.php");
        exit();
    } else {
        $login_message = "Incorrect name or password.";
    }
    $stmt->close();
    $conn->close();
}
?>
<?php include 'darkmode_auto.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Volunteer Registration & Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: var(--bg-primary);
            font-family: Arial, sans-serif;
            min-height: 100vh;
            margin: 0;
            transition: background-color 0.3s ease, color 0.3s ease;
            color: var(--text-primary);
        }
        .main-container {
            background: var(--bg-secondary);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 32px var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            max-width: 900px;
            width: 100%;
            margin: 40px auto;
        }
        .page-title {
            text-align: center;
            color: var(--accent);
            font-size: 2.5em;
            margin-bottom: 40px;
            font-weight: bold;
        }
        .forms-flex {
            display: flex;
            flex-direction: row;
            gap: 40px;
            justify-content: center;
            align-items: flex-start;
        }
        .option-card {
            background: var(--bg-primary);
            padding: 30px;
            border-radius: 12px;
            border: 2px solid var(--accent);
            box-shadow: 0 0 16px var(--accent);
            min-width: 350px;
            flex: 1;
        }
        .card-title {
            color: var(--accent);
            font-size: 1.8em;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 1.1em;
            color: var(--text-primary);
            font-weight: bold;
            background: var(--accent);
            color: #fff;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid var(--border);
            box-shadow: 0 2px 4px var(--shadow);
        }
        input[type="text"], input[type="tel"], input[type="password"] {
            width: 100%;
            padding: 12px;
            font-size: 1em;
            border: 2px solid var(--accent);
            border-radius: 8px;
            margin-bottom: 8px;
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px var(--shadow);
        }
        input[type="text"]:focus, input[type="tel"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(77, 171, 247, 0.2);
            background: var(--bg-primary);
            color: var(--text-primary);
        }
        .btn {
            width: 100%;
            padding: 15px;
            font-size: 1.2em;
            background-color: var(--accent);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
            font-weight: bold;
        }
        .btn:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--shadow);
        }
        .message, .login-message {
            margin-top: 20px;
            font-size: 1.1em;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
        }
        .message {
            color: #28a745;
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid #28a745;
        }
        .login-message {
            color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid #dc3545;
        }
        .back-btn {
            display: block;
            text-align: center;
            margin-top: 30px;
            padding: 12px 24px;
            background-color: var(--text-secondary);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: bold;
        }
        .back-btn:hover {
            background-color: var(--text-primary);
            transform: translateY(-2px);
        }
        @media (max-width: 900px) {
            .forms-flex {
                flex-direction: column;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <h1 class="page-title">🚨 Volunteer Portal</h1>
        <div class="forms-flex">
            <div class="option-card">
                <h2 class="card-title">📝 New Volunteer Registration</h2>
                <form method="post" action="volunteer.php">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Current Address</label>
                        <input type="text" id="location" name="location" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Mobile Number</label>
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
                    <button class="btn" type="submit" name="register">Register as Volunteer</button>
                </form>
                <?php if (!empty($message)): ?>
                    <div class="message"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
            </div>
            <div class="option-card">
                <h2 class="card-title">🔐 Existing Volunteer Login</h2>
                <form method="post" action="volunteer.php">
                    <div class="form-group">
                        <label for="login_name">Volunteer Name</label>
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
        </div>
        <a href="user_interface.php" class="back-btn">← Back to User Interface</a>
    </div>
</body>
</html>
