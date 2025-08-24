<?php
// Dark Mode Template - Copy this pattern to any page you want to add dark mode to

// Include dark mode functionality
include 'darkmode_include.php';

// Your existing PHP code here...
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Your Page Title</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Your custom styles here */
        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .your-container {
            background-color: var(--bg-secondary);
            border: 1px solid var(--border);
            box-shadow: 0 10px 30px var(--shadow);
        }
        
        .your-text {
            color: var(--text-secondary);
        }
        
        /* Add more custom styles using CSS variables */
    </style>
</head>
<body data-theme="<?php echo getThemeAttribute(); ?>">
    
    <!-- Dark Mode Toggle Button -->
    <?php echo getThemeToggleButton(); ?>
    
    <!-- Your page content here -->
    <div class="your-container">
        <h1>Your Content</h1>
        <p class="your-text">This is your page content with dark mode support.</p>
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
