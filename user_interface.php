<?php include 'darkmode_auto.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Interface</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-primary);
            font-family: Arial, sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
            color: var(--text-primary);
        }
        .container {
            background: var(--bg-secondary);
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 4px 24px var(--shadow);
            text-align: center;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            max-width: 600px;
        }
        .question {
            font-size: 1.4em;
            margin: 30px 0 15px 0;
            color: var(--text-secondary);
            font-weight: bold;
        }
        .btn {
            display: inline-block;
            margin: 10px 15px;
            padding: 15px 30px;
            font-size: 1.2em;
            background-color: var(--accent);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 100px;
            text-decoration: none;
            font-weight: bold;
        }
        .btn:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--shadow);
        }
        .btn-yes {
            background-color: #28a745;
        }
        .btn-yes:hover {
            background-color: #218838;
        }
        .btn-no {
            background-color: #6c757d;
        }
        .btn-no:hover {
            background-color: #5a6268;
        }
        h2 {
            margin-bottom: 30px;
            font-size: 2em;
            color: var(--text-primary);
        }
        .button-group {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px 0 30px 0;
        }
        .question-section {
            margin: 40px 0;
            padding: 20px;
            background: var(--bg-primary);
            border-radius: 10px;
            border: 1px solid var(--border);
        }
    </style>
</head>
<body>
    
    <div class="container">
        <h2>Welcome! Please choose an option:</h2>
        
        <div class="question-section">
            <div class="question">Are you a victim?</div>
            <div class="button-group">
                <button class="btn btn-yes" onclick="location.href='victim.php'">Yes</button>
                <button class="btn btn-no" onclick="location.href='login.php'">No</button>
            </div>
        </div>
        
        <div class="question-section">
            <div class="question">Are you a volunteer?</div>
            <div class="button-group">
                <button class="btn btn-yes" onclick="location.href='volunteer.php'">Yes</button>
                <button class="btn btn-no" onclick="location.href='login.php'">No</button>
            </div>
        </div>
    </div>

</body>
</html>
