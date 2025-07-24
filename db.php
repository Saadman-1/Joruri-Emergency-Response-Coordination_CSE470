<?php
$host = "localhost";       // Usually localhost
$user = "root";            // Database username
$password = "";            // Database password (often empty in localhost)
$dbname = "database470";    // Your database name

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";
?>
