<?php include 'darkmode_auto.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Joruri AI Chatbot</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: var(--bg-primary);
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            transition: background-color 0.3s ease, color 0.3s ease;
            color: var(--text-primary);
        }
        .container {
            background: var(--bg-secondary);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 32px var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        h1 {
            color: var(--accent);
            font-size: 2.5em;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .description {
            color: var(--text-secondary);
            font-size: 1.1em;
            margin-bottom: 40px;
            line-height: 1.6;
        }
        .button-group {
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }
        .btn {
            padding: 15px 30px;
            font-size: 1.2em;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            min-width: 200px;
        }
        .btn-primary {
            background-color: var(--accent);
            color: white;
        }
        .btn-primary:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--shadow);
        }
        .btn-secondary {
            background-color: var(--text-secondary);
            color: white;
        }
        .btn-secondary:hover {
            background-color: var(--text-primary);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--shadow);
        }
        .chatbot-icon {
            font-size: 3em;
            margin-bottom: 20px;
            display: block;
        }
        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
                margin: 20px;
            }
            .btn {
                min-width: 180px;
                padding: 12px 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <span class="chatbot-icon">🤖</span>
        <h1>Joruri AI Chatbot</h1>
        
        <div class="description">
            Get instant help and support from our AI-powered chatbot. 
            Click the button below to start chatting with Joruri AI.
        </div>
        
        <div class="button-group">
            <a href="https://www.chatbase.co/chatbot-iframe/yBSH4Lt5lLeHZZLD5GDLV" 
               target="_blank" 
               class="btn btn-primary">
                🚀 Open Chatbot
            </a>
            
            <button onclick="goBack()" class="btn btn-secondary">
                ← Go Back
            </button>
        </div>
    </div>

    <script>
        function goBack() {
            if (document.referrer) {
                window.history.back();
            } else {
                // If no referrer, redirect to a default page
                window.location.href = 'user_interface.php';
            }
        }
    </script>
</body>
</html>
