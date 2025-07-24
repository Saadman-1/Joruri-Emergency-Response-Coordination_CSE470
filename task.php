<?php
session_start();
if (!isset($_SESSION['volunteer_name'])) {
    header('Location: volunteer.php');
    exit();
}

$volunteer_name = $_SESSION['volunteer_name'];
$volunteer_location = '';
$victims = [];
$message_status = '';

$conn = new mysqli("localhost", "root", "", "database470");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Get volunteer's location
$stmt = $conn->prepare("SELECT location FROM volunteer WHERE name=? LIMIT 1");
$stmt->bind_param("s", $volunteer_name);
$stmt->execute();
$stmt->bind_result($volunteer_location);
$stmt->fetch();
$stmt->close();

if ($volunteer_location) {
    // Get victims at the same location
    $stmt = $conn->prepare("SELECT name, phone, location, calamity, need, `blood-group` FROM victim WHERE location=?");
    $stmt->bind_param("s", $volunteer_location);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $victims[] = $row;
    }
    $stmt->close();
}

// --- Victim Search/Message Logic ---
$search_victim_details = null;
$search_vic_message = '';
$search_status = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['victim_search'])) {
    $search_victim_name = $_POST['search_victim_name'] ?? '';
    $search_volunteer_name = $_POST['search_volunteer_name'] ?? '';
    // Get victim details
    $stmt = $conn->prepare("SELECT name, phone, location, calamity, need, `blood-group` FROM victim WHERE name=? LIMIT 1");
    $stmt->bind_param("s", $search_victim_name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $search_victim_details = $result->fetch_assoc();
    }
    $stmt->close();
    // Get vic_message from messages table
    $stmt = $conn->prepare("SELECT id, vic_message FROM messages WHERE victim=? AND volunteer=? AND vic_message != '' LIMIT 1");
    $stmt->bind_param("ss", $search_victim_name, $search_volunteer_name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $search_vic_message = $row['vic_message'];
        $search_msg_id = $row['id'];
    } else {
        $search_vic_message = '';
        $search_msg_id = '';
    }
    $stmt->close();
}
// Handle volunteer reply to victim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_vol_message'])) {
    $msg_id = $_POST['msg_id'] ?? '';
    $vol_message = $_POST['vol_message'] ?? '';
    $conn2 = new mysqli("localhost", "root", "", "database470");
    if ($conn2->connect_error) {
        $search_status = "Connection failed: " . $conn2->connect_error;
    } else {
        // Update vol_message and clear vic_message
        $stmt2 = $conn2->prepare("UPDATE messages SET vol_message=?, vic_message='' WHERE id=?");
        $stmt2->bind_param("si", $vol_message, $msg_id);
        if ($stmt2->execute()) {
            $search_status = "Message sent to victim.";
        } else {
            $search_status = "Failed to send message.";
        }
        $stmt2->close();
    }
    $conn2->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Assigned Tasks</title>
    <style>
        body {
            background: #f4f4f4;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 800px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px 8px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        th {
            background: #f8f8f8;
        }
        .no-tasks {
            text-align:center; color:#dc3545; margin-top:20px;
        }
        .msg-btn {
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 6px 16px;
            cursor: pointer;
            font-size: 1em;
        }
        .msg-btn:hover {
            background: #0056b3;
        }
        .popup {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.3);
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .popup-content {
            background: #fff;
            padding: 30px 24px;
            border-radius: 10px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.15);
            min-width: 300px;
            max-width: 90vw;
        }
        .popup-content h3 {
            margin-top: 0;
            margin-bottom: 18px;
            color: #007bff;
        }
        .popup-content textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 1em;
            min-height: 80px;
            resize: vertical;
        }
        .popup-content button {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: none;
            background: #007bff;
            color: #fff;
            font-size: 1em;
            cursor: pointer;
        }
        .popup-content button:hover {
            background: #0056b3;
        }
        .close-btn {
            background: #dc3545;
            margin-top: 8px;
        }
        .close-btn:hover {
            background: #a71d2a;
        }
        .msg-status {
            color: green;
            text-align: center;
            margin-top: 10px;
        }
        .no-msgs {
            text-align:center; color:#dc3545; margin-top:10px;
        }
        .search-section {
            background: #fff;
            padding: 30px 30px 20px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            max-width: 500px;
            margin: 30px auto 0 auto;
        }
        .search-header {
            font-size: 1.2em;
            color: #007bff;
            margin-bottom: 18px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Assigned Tasks for <?php echo htmlspecialchars($volunteer_name); ?></h2>
    <?php if ($victims): ?>
        <table>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Location</th>
                <th>Calamity</th>
                <th>Need</th>
                <th>Blood Group</th>
                <th>Action</th>
            </tr>
            <?php foreach ($victims as $v): ?>
                <tr>
                    <td><?php echo htmlspecialchars($v['name']); ?></td>
                    <td><?php echo htmlspecialchars($v['phone']); ?></td>
                    <td><?php echo htmlspecialchars($v['location']); ?></td>
                    <td><?php echo htmlspecialchars($v['calamity']); ?></td>
                    <td><?php echo htmlspecialchars($v['need']); ?></td>
                    <td><?php echo htmlspecialchars($v['blood-group']); ?></td>
                    <td><button class="msg-btn" onclick="openMsgPopup('<?php echo htmlspecialchars($v['name']); ?>')">Message</button></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <div class="no-tasks">No tasks assigned at your location.</div>
    <?php endif; ?>
</div>
<div class="search-section">
    <div class="search-header">If you are already helping, search victim here</div>
    <form method="post" style="text-align:center; margin-bottom:18px;">
        <input type="text" name="search_victim_name" placeholder="Victim Name" required style="padding:8px; border-radius:6px; border:1px solid #ccc; width:40%;">
        <input type="text" name="search_volunteer_name" placeholder="Volunteer Name" required style="padding:8px; border-radius:6px; border:1px solid #ccc; width:40%;">
        <button type="submit" name="victim_search" class="msg-btn">Search</button>
    </form>
    <?php if (isset($search_victim_name) && !$search_victim_details): ?>
        <div class="no-msgs">No victim found with that name.</div>
    <?php elseif ($search_victim_details): ?>
        <table class="msg-table">
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Location</th>
                <th>Calamity</th>
                <th>Need</th>
                <th>Blood Group</th>
            </tr>
            <tr>
                <td><?php echo htmlspecialchars($search_victim_details['name']); ?></td>
                <td><?php echo htmlspecialchars($search_victim_details['phone']); ?></td>
                <td><?php echo htmlspecialchars($search_victim_details['location']); ?></td>
                <td><?php echo htmlspecialchars($search_victim_details['calamity']); ?></td>
                <td><?php echo htmlspecialchars($search_victim_details['need']); ?></td>
                <td><?php echo htmlspecialchars($search_victim_details['blood-group']); ?></td>
            </tr>
        </table>
        <?php if ($search_vic_message): ?>
            <div style="margin-bottom:10px;"><strong>Victim's Message:</strong> <?php echo htmlspecialchars($search_vic_message); ?></div>
            <button class="msg-btn" onclick="openVolMsgPopup('<?php echo $search_msg_id; ?>')">Reply to Victim</button>
        <?php endif; ?>
    <?php endif; ?>
    <?php if (!empty($search_status)): ?>
        <div class="msg-status"><?php echo htmlspecialchars($search_status); ?></div>
    <?php endif; ?>
</div>
<div class="popup" id="volMsgPopup">
    <div class="popup-content">
        <h3>Send Message to Victim</h3>
        <form method="post" action="task.php">
            <input type="hidden" name="msg_id" id="vol_msg_id">
            <textarea name="vol_message" id="vol_message" placeholder="Type your message..." required></textarea>
            <input type="hidden" name="send_vol_message" value="1">
            <button type="submit">Send</button>
            <button type="button" class="close-btn" onclick="closeVolMsgPopup()">Cancel</button>
        </form>
    </div>
</div>
<script>
function openMsgPopup(victimName) {
    document.getElementById('victim_name').value = victimName;
    document.getElementById('vol_message').value = '';
    document.getElementById('msgPopup').style.display = 'flex';
}
function closeMsgPopup() {
    document.getElementById('msgPopup').style.display = 'none';
}
function openVolMsgPopup(msgId) {
    document.getElementById('vol_msg_id').value = msgId;
    document.getElementById('vol_message').value = '';
    document.getElementById('volMsgPopup').style.display = 'flex';
}
function closeVolMsgPopup() {
    document.getElementById('volMsgPopup').style.display = 'none';
}
</script>
</body>
</html>
