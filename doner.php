<?php
$conn = new mysqli("localhost", "root", "", "database470");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$takerData = null;
$searchName = "";
$takers = [];

// Fetch all takers
$allResult = $conn->query("SELECT * FROM taker ORDER BY name ASC");
while ($row = $allResult->fetch_assoc()) {
    $takers[] = $row;
}

// Handle search
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_name'])) {
    $searchName = $conn->real_escape_string($_POST['search_name']);
    $sql = "SELECT * FROM taker WHERE name LIKE '%$searchName%' LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $takerData = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donor Portal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
        }
        .search-box {
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 8px;
            width: 300px;
            font-size: 16px;
        }
        button {
            padding: 8px 16px;
            font-size: 16px;
            cursor: pointer;
        }
        .taker-list {
            margin-top: 20px;
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;
            width: 400px;
        }
        .taker-list ul {
            list-style: none;
            padding: 0;
        }
        .taker-list li {
            padding: 6px 0;
            cursor: pointer;
            color: #007bff;
        }
        .taker-list li:hover {
            text-decoration: underline;
        }
        .taker-info {
            margin-top: 30px;
            border: 1px solid #ccc;
            padding: 20px;
            width: 500px;
        }
        .donate-button {
            margin-top: 20px;
        }
        .donate-button a {
            background-color: #e2136e;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
    <script>
        function selectTaker(name) {
            document.getElementById('search_name').value = name;
            document.getElementById('searchForm').submit();
        }
    </script>
</head>
<body>

<h2>Search Donation Taker</h2>

<div class="search-box">
    <form method="POST" action="doner.php" id="searchForm">
        <input type="text" name="search_name" id="search_name" placeholder="Enter name to search" value="<?= htmlspecialchars($searchName) ?>" />
        <button type="submit">Search</button>
    </form>
</div>

<div class="taker-list">
    <h3>All Donation Takers</h3>
    <ul>
        <?php foreach ($takers as $taker): ?>
            <li onclick="selectTaker('<?= htmlspecialchars($taker['name']) ?>')">
                <?= htmlspecialchars($taker['name']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php if ($takerData): ?>
    <div class="taker-info">
        <h3>Donation Taker Details</h3>
        <p><strong>Name:</strong> <?= htmlspecialchars($takerData['name']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($takerData['address']) ?></p>
        <p><strong>Reason:</strong> <?= htmlspecialchars($takerData['reason']) ?></p>
        <p><strong>Needed Amount:</strong> <?= htmlspecialchars($takerData['needed_amount']) ?> BDT</p>
        <p><strong>bKash Number:</strong> <?= htmlspecialchars($takerData['bkash']) ?></p>
        <p><strong>Bank Account:</strong> <?= htmlspecialchars($takerData['bank_account']) ?></p>
        <p><strong>Proof:</strong> <?= htmlspecialchars($takerData['proof']) ?></p>
        <p><strong>Date of Application:</strong> <?= htmlspecialchars($takerData['date_of_application']) ?></p>

        <div class="donate-button">
            <a href="https://www.bkash.com/app" target="_blank">
                Donate Now Using bKash
            </a>
        </div>
    </div>
<?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <p>No taker found with that name.</p>
<?php endif; ?>

</body>
</html>
