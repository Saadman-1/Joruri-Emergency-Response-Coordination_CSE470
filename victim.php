<?php include 'darkmode_auto.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Victim Options</title>
    <style>
        body {
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .container {
            background: #fff;
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            text-align: center;
        }
        .btn {
            display: block;
            width: 100%;
            margin: 20px 0;
            padding: 20px;
            font-size: 1.3em;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        h2 {
            margin-bottom: 30px;
            font-size: 2em;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Victim Options</h2>
        <button class="btn" onclick="location.href='chatbot.php'">Joruri-chatbot</button>
        <button class="btn" onclick="location.href='helpline.php'">Jorurin helpline</button>
        <button class="btn" onclick="location.href='victim_registration.php'">Fill up joruri form</button>
    </div>
</body>
</html>
