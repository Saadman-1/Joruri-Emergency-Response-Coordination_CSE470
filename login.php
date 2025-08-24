<?php
session_start();

// Handle dark mode toggle
if (isset($_POST['toggle_darkmode'])) {
    if (isset($_SESSION['darkmode'])) {
        $_SESSION['darkmode'] = !$_SESSION['darkmode'];
    } else {
        $_SESSION['darkmode'] = true;
    }
}

// Set default dark mode preference
if (!isset($_SESSION['darkmode'])) {
    $_SESSION['darkmode'] = false;
}

// Get current theme
$isDarkMode = $_SESSION['darkmode'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['role'])) {
        if ($_POST['role'] == "admin") {
            header("Location: admin.php");
            exit();
        } elseif ($_POST['role'] == "user") {
            header("Location: user_interface.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Joruri</title>
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            /* Light theme variables */
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --text-primary: #333333;
            --text-secondary: #444444;
            --text-tertiary: #222222;
            --shadow: rgba(0, 0, 0, 0.1);
            --accent: #007bff;
            --accent-hover: #0056b3;
            --border: #e9ecef;
        }

        [data-theme="dark"] {
            /* Dark theme variables */
            --bg-primary: #1a1a1a;
            --bg-secondary: #2d2d2d;
            --text-primary: #ffffff;
            --text-secondary: #e0e0e0;
            --text-tertiary: #cccccc;
            --shadow: rgba(0, 0, 0, 0.3);
            --accent: #4dabf7;
            --accent-hover: #339af0;
            --border: #404040;
        }

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

        form {
            background-color: var(--bg-secondary);
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 10px 30px var(--shadow);
            width: 100%;
            max-width: 480px;
            text-align: center;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .title {
            font-size: 26px;
            color: var(--text-primary);
            margin-bottom: 30px;
            font-weight: bold;
            position: relative;
        }

        /* Red cross symbol before title */
        .title::before {
            content: "✚";
            color: red;
            font-size: 30px;
            margin-right: 10px;
            vertical-align: middle;
        }

        /* Questions styling */
        p {
            color: var(--text-secondary);
            font-size: 18px;
            font-weight: 500;
            margin: 25px 0 10px;
        }

        /* Radio buttons */
        input[type="radio"] {
            margin-right: 10px;
            transform: scale(1.2);
            accent-color: var(--accent);
            cursor: pointer;
        }

        /* Labels next to radios */
        label {
            font-size: 17px;
            color: var(--text-tertiary);
            cursor: pointer;
        }

        /* Submit button styling */
        input[type="submit"] {
            margin-top: 35px;
            padding: 14px 28px;
            background-color: var(--accent);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        input[type="submit"]:hover {
            background-color: var(--accent-hover);
            transform: scale(1.03);
        }

        .dark-mode-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--accent);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px var(--shadow);
            z-index: 1000;
        }

        .dark-mode-toggle:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--shadow);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .dark-mode-toggle {
                top: 15px;
                right: 15px;
                padding: 10px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body data-theme="<?php echo $isDarkMode ? 'dark' : 'light'; ?>">
    
    <!-- Dark Mode Toggle Button -->
    <form method="POST" style="position: fixed; top: 20px; right: 20px; background: none; box-shadow: none; padding: 0; width: auto; max-width: none; border: none; z-index: 1000;">
        <button type="submit" name="toggle_darkmode" class="dark-mode-toggle">
            <?php echo $isDarkMode ? '☀️ Light Mode' : '🌙 Dark Mode'; ?>
        </button>
    </form>

    <form method="post" action="login.php">
        <!-- Title with Red Cross -->
        <h2 class="title">Joruri – Emergency Response Coordination</h2>

        <p>Are you an admin?</p>
        <input type="radio" name="role" value="admin" id="admin">
        <label for="admin">Yes</label>

        <p>Are you a user?</p>
        <input type="radio" name="role" value="user" id="user">
        <label for="admin">Yes</label>

        <br><br>
        <input type="submit" value="Submit">
    </form>

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
