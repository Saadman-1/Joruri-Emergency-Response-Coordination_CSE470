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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dark Mode Demo</title>
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

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
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

        .container {
            text-align: center;
            max-width: 600px;
            padding: 20px;
        }

        .theme-toggle {
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
        }

        .theme-toggle:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--shadow);
        }

        .demo-card {
            background-color: var(--bg-secondary);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px var(--shadow);
            margin-bottom: 30px;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .demo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px var(--shadow);
        }

        .title {
            font-size: 28px;
            color: var(--text-primary);
            margin-bottom: 20px;
            font-weight: bold;
        }

        .subtitle {
            font-size: 18px;
            color: var(--text-secondary);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .feature-item {
            background: var(--bg-primary);
            padding: 25px;
            border-radius: 12px;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px var(--shadow);
        }

        .feature-icon {
            font-size: 40px;
            margin-bottom: 15px;
            display: block;
        }

        .feature-title {
            font-size: 18px;
            font-weight: bold;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .feature-desc {
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .status-indicator {
            display: inline-block;
            padding: 8px 16px;
            background: var(--accent);
            color: white;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
        }

        /* Form styling for consistency */
        form {
            background-color: var(--bg-secondary);
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 10px 30px var(--shadow);
            width: 100%;
            max-width: 480px;
            text-align: center;
            border: 1px solid var(--border);
        }

        input[type="text"], input[type="email"], input[type="password"], textarea, select {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(77, 171, 247, 0.1);
        }

        input[type="submit"], button {
            padding: 14px 28px;
            background-color: var(--accent);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        input[type="submit"]:hover, button:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            .demo-card {
                padding: 25px;
            }
            
            .feature-grid {
                grid-template-columns: 1fr;
            }
            
            .theme-toggle {
                top: 15px;
                right: 15px;
                padding: 10px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body data-theme="<?php echo $isDarkMode ? 'dark' : 'light'; ?>">
    
    <!-- Theme Toggle Button -->
    <form method="POST" style="position: fixed; top: 20px; right: 20px; background: none; box-shadow: none; padding: 0; width: auto; max-width: none; border: none;">
        <button type="submit" name="toggle_darkmode" class="theme-toggle">
            <?php echo $isDarkMode ? '☀️ Light Mode' : '🌙 Dark Mode'; ?>
        </button>
    </form>

    <div class="container">
        <div class="demo-card">
            <h1 class="title">Dark Mode Implementation</h1>
            <p class="subtitle">
                This demonstrates a complete dark mode system with smooth transitions, 
                CSS variables, and persistent user preferences.
            </p>
            
            <div class="status-indicator">
                Current Theme: <?php echo $isDarkMode ? 'Dark' : 'Light'; ?>
            </div>
        </div>

        <div class="feature-grid">
            <div class="feature-item">
                <span class="feature-icon">🎨</span>
                <div class="feature-title">CSS Variables</div>
                <div class="feature-desc">
                    Uses CSS custom properties for easy theme switching and consistent styling across components.
                </div>
            </div>
            
            <div class="feature-item">
                <span class="feature-icon">💾</span>
                <div class="feature-title">Session Persistence</div>
                <div class="feature-desc">
                    Remembers user's theme preference using PHP sessions for a seamless experience.
                </div>
            </div>
            
            <div class="feature-item">
                <span class="feature-icon">⚡</span>
                <div class="feature-title">Smooth Transitions</div>
                <div class="feature-desc">
                    Elegant animations and transitions when switching between light and dark themes.
                </div>
            </div>
            
            <div class="feature-item">
                <span class="feature-icon">📱</span>
                <div class="feature-title">Responsive Design</div>
                <div class="feature-desc">
                    Optimized for all screen sizes with mobile-first responsive design principles.
                </div>
            </div>
        </div>

        <!-- Example Form with Dark Mode Styling -->
        <div class="demo-card">
            <h2 class="title">Example Form</h2>
            <form>
                <input type="text" placeholder="Enter your name" required>
                <input type="email" placeholder="Enter your email" required>
                <textarea placeholder="Enter your message" rows="4"></textarea>
                <input type="submit" value="Submit">
            </form>
        </div>
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
