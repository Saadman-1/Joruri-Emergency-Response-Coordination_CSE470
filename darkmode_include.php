<?php
// Global Dark Mode Include File - Automatically applies dark mode to ALL pages

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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

// Function to get theme attribute for body tag
function getThemeAttribute() {
    global $isDarkMode;
    return $isDarkMode ? 'dark' : 'light';
}

// Function to get theme toggle button HTML
function getThemeToggleButton() {
    global $isDarkMode;
    $buttonText = $isDarkMode ? '☀️ Light Mode' : '🌙 Dark Mode';
    
    return '
    <form method="POST" style="position: fixed; top: 20px; right: 20px; background: none; box-shadow: none; padding: 0; width: auto; max-width: none; border: none; z-index: 1000;">
        <button type="submit" name="toggle_darkmode" class="dark-mode-toggle">
            ' . $buttonText . '
        </button>
    </form>';
}

// Function to get theme toggle button with custom positioning
function getThemeToggleButtonCustom($top = '20px', $right = '20px') {
    global $isDarkMode;
    $buttonText = $isDarkMode ? '☀️ Light Mode' : '🌙 Dark Mode';
    
    return '
    <form method="POST" style="position: fixed; top: ' . $top . '; right: ' . $right . '; background: none; box-shadow: none; padding: 0; width: auto; max-width: none; border: none; z-index: 1000;">
        <button type="submit" name="toggle_darkmode" class="dark-mode-toggle">
            ' . $buttonText . '
        </button>
    </form>';
}

// Function to get global dark mode JavaScript that applies to ALL pages
function getGlobalDarkModeScript() {
    global $isDarkMode;
    $theme = $isDarkMode ? 'dark' : 'light';
    
    return '
    <script>
    // Global Dark Mode Script - Automatically applies to ALL pages
    (function() {
        // Check if dark mode is enabled in session
        const isDarkMode = ' . ($isDarkMode ? 'true' : 'false') . ';
        const theme = "' . $theme . '";
        
        // Apply theme immediately
        function applyTheme() {
            const body = document.body;
            
            // Set theme attribute
            body.setAttribute("data-theme", theme);
            
            // Apply CSS variables to all elements
            const root = document.documentElement;
            
            if (isDarkMode) {
                // Dark theme colors
                root.style.setProperty("--bg-primary", "#1a1a1a");
                root.style.setProperty("--bg-secondary", "#2d2d2d");
                root.style.setProperty("--text-primary", "#ffffff");
                root.style.setProperty("--text-secondary", "#e0e0e0");
                root.style.setProperty("--text-tertiary", "#cccccc");
                root.style.setProperty("--shadow", "rgba(0, 0, 0, 0.3)");
                root.style.setProperty("--accent", "#4dabf7");
                root.style.setProperty("--accent-hover", "#339af0");
                root.style.setProperty("--border", "#404040");
                
                // Apply dark mode to body
                body.style.backgroundColor = "#1a1a1a";
                body.style.color = "#ffffff";
            } else {
                // Light theme colors
                root.style.setProperty("--bg-primary", "#ffffff");
                root.style.setProperty("--bg-secondary", "#f8f9fa");
                root.style.setProperty("--text-primary", "#333333");
                root.style.setProperty("--text-secondary", "#444444");
                root.style.setProperty("--text-tertiary", "#222222");
                root.style.setProperty("--shadow", "rgba(0, 0, 0, 0.1)");
                root.style.setProperty("--accent", "#007bff");
                root.style.setProperty("--accent-hover", "#0056b3");
                root.style.setProperty("--border", "#e9ecef");
                
                // Apply light mode to body
                body.style.backgroundColor = "#ffffff";
                body.style.color = "#333333";
            }
            
            // Apply theme to common elements
            applyThemeToElements();
        }
        
        // Apply theme to common HTML elements
        function applyThemeToElements() {
            const elements = {
                "h1, h2, h3, h4, h5, h6": "color",
                "p, span, div": "color",
                "label": "color",
                "input, textarea, select": ["backgroundColor", "color", "borderColor"],
                "button, .btn": "backgroundColor",
                ".container, .box, .form, .card": ["backgroundColor", "borderColor", "boxShadow"]
            };
            
            for (const selector in elements) {
                const properties = Array.isArray(elements[selector]) ? elements[selector] : [elements[selector]];
                const domElements = document.querySelectorAll(selector);
                
                domElements.forEach(el => {
                    properties.forEach(prop => {
                        if (prop === "color") {
                            el.style.color = isDarkMode ? "#ffffff" : "#333333";
                        } else if (prop === "backgroundColor") {
                            if (el.tagName === "INPUT" || el.tagName === "TEXTAREA" || el.tagName === "SELECT") {
                                el.style.backgroundColor = isDarkMode ? "#1a1a1a" : "#ffffff";
                            } else if (el.tagName === "BUTTON" || el.classList.contains("btn")) {
                                el.style.backgroundColor = isDarkMode ? "#4dabf7" : "#007bff";
                            } else {
                                el.style.backgroundColor = isDarkMode ? "#2d2d2d" : "#f8f9fa";
                            }
                        } else if (prop === "borderColor") {
                            el.style.borderColor = isDarkMode ? "#404040" : "#e9ecef";
                        } else if (prop === "boxShadow") {
                            el.style.boxShadow = isDarkMode ? "0 4px 24px rgba(0, 0, 0, 0.3)" : "0 4px 24px rgba(0, 0, 0, 0.08)";
                        }
                    });
                });
            }
        }
        
        // Apply theme when DOM is loaded
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", applyTheme);
        } else {
            applyTheme();
        }
        
        // Apply theme to dynamically added content
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === "childList") {
                    applyThemeToElements();
                }
            });
        });
        
        // Start observing
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
        
        // Add smooth transitions
        setTimeout(() => {
            document.body.style.transition = "background-color 0.3s ease, color 0.3s ease";
            const style = document.createElement("style");
            style.textContent = `
                * { transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease; }
            `;
            document.head.appendChild(style);
        }, 100);
        
    })();
    </script>';
}

// Function to get minimal dark mode include for any page
function getMinimalDarkModeInclude() {
    return getGlobalDarkModeScript();
}
?>
