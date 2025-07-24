<?php
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
<html>
<head>
    <title>Joruri</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="post" action="login.php">
        <!-- Title with Red Cross -->
        <h2 class="title">Joruri – Emergency Response Coordination</h2>

        <p>Are you an admin?</p>
        <input type="radio" name="role" value="admin" id="admin">
        <label for="admin">Yes</label>

        <p>Are you a user?</p>
        <input type="radio" name="role" value="user" id="user">
        <label for="user">Yes</label>

        <br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
