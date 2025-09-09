<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "database470");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Collect form data
    $name = $_POST['name'];
    $address = $_POST['address'];
    $reason = $_POST['reason'];
    $needed_amount = $_POST['needed_amount'];
    $bkash = $_POST['bkash'];
    $bank_account = $_POST['bank_account'];
    $proof = $_POST['proof'];
    $date_of_application = date("Y-m-d"); // Automatically set today's date

    // Prepare and execute SQL
    $sql = "INSERT INTO taker (name, address, reason, needed_amount, bkash, bank_account, proof, date_of_application)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiiiss", $name, $address, $reason, $needed_amount, $bkash, $bank_account, $proof, $date_of_application);

    if ($stmt->execute()) {
        echo "<script>alert('Application submitted successfully!');</script>";
    } else {
        echo "<script>alert('Error submitting application.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: space-between;
            padding: 40px;
        }
        .form-container {
            width: 60%;
        }
        .donor-button {
            width: 30%;
            display: flex;
            justify-content: flex-end;
            align-items: flex-start;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        input, textarea {
            padding: 8px;
            font-size: 16px;
            width: 100%;
        }
        button {
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
        }
        .donor-button a {
            text-decoration: none;
            background-color: #28a745;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Donation Taker Application</h2>
    <form method="POST" action="donations.php">
        <input type="text" name="name" placeholder="Your Name" required />
        <input type="text" name="address" placeholder="Your Address" required />
        <textarea name="reason" placeholder="Reason for Donation" required></textarea>
        <input type="number" name="needed_amount" placeholder="Amount Needed (BDT)" required />
        <input type="number" name="bkash" placeholder="bKash Number" required />
        <input type="number" name="bank_account" placeholder="Bank Account Number" required />
        <input type="text" name="proof" placeholder="Proof Document (e.g. ID, Certificate)" required />
        <button type="submit">Submit Application</button>
    </form>
</div>

<div class="donor-button">
    <a href="doner.php">Give Donation</a>
</div>

</body>
</html>
