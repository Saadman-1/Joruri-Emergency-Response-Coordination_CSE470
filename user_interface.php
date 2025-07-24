<!DOCTYPE html>
<html>
<head>
    <title>User Interface</title>
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
        .question {
            font-size: 1.4em;
            margin: 30px 0 10px 0;
            color: #333;
        }
        .btn {
            display: inline-block;
            margin: 10px 0 20px 0;
            padding: 12px 40px;
            font-size: 1.2em;
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
        <h2>Welcome! Please choose an option:</h2>
        <div class="question">Are you a victim?</div>
        <button class="btn" onclick="location.href='victim.php'">Yes</button>
        <div class="question">Are you a volunteer?</div>
        <button class="btn" onclick="location.href='volunteer.php'">Yes</button>
    </div>
</body>
</html>
