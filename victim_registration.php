<?php
// Include dark mode functionality
include 'darkmode_auto.php';

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $location = $_POST['location'] ?? '';
    $calamity = $_POST['calamity'] ?? '';
    $need = $_POST['need'] ?? '';
    $blood_group = $_POST['blood_group'] ?? '';
    $safe = $_POST['safe'] ?? '';

    $conn = new mysqli("localhost", "root", "", "database470");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($safe === "no") {
        // Insert into victim table (id and r-date are auto-handled)
        $stmt = $conn->prepare("INSERT INTO victim (name, phone, location, calamity, need, `blood-group`) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            $message = "Prepare failed: " . $conn->error;
        } else {
            $stmt->bind_param("ssssss", $name, $phone, $location, $calamity, $need, $blood_group);
            if ($stmt->execute()) {
                $message = "Your information has been submitted. Help is on the way.";
            } else {
                $message = "Execute failed: " . $stmt->error;
            }
            $stmt->close();
        }
    } elseif ($safe === "yes") {
        // Delete from victim table
        $stmt = $conn->prepare("DELETE FROM victim WHERE name=? AND phone=?");
        if (!$stmt) {
            $message = "Prepare failed: " . $conn->error;
        } else {
            $stmt->bind_param("ss", $name, $phone);
            if ($stmt->execute()) {
                $message = "We are glad you are safe. Your record has been removed.";
            } else {
                $message = "Execute failed: " . $stmt->error;
            }
            $stmt->close();
        }
    }
    $conn->close();
}

// --- MESSAGE CHECK/REPLY LOGIC ---
$msg_search_result = [];
$msg_search_name = '';
$msg_status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['msg_search'])) {
    $msg_search_name = $_POST['msg_search_name'] ?? '';
    $conn = new mysqli("localhost", "root", "", "database470");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("SELECT id, volunteer, vol_message FROM messages WHERE victim=? AND vol_message != ''");
    $stmt->bind_param("s", $msg_search_name);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $msg_search_result[] = $row;
    }
    $stmt->close();
    $conn->close();
}

// Handle victim reply
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_vic_message'])) {
    $msg_id = $_POST['msg_id'] ?? '';
    $vic_message = $_POST['vic_message'] ?? '';
    $conn = new mysqli("localhost", "root", "", "database470");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Update vic_message and clear vol_message
    $stmt = $conn->prepare("UPDATE messages SET vic_message=?, vol_message='' WHERE id=?");
    $stmt->bind_param("si", $vic_message, $msg_id);
    if ($stmt->execute()) {
        $msg_status = "Your message has been sent to the volunteer.";
    } else {
        $msg_status = "Failed to send your message.";
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Joruri Victim Registration</title>
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
            max-width: 400px;
            width: 100%;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }
        h2 {
            margin-bottom: 30px;
            font-size: 2em;
            color: var(--text-primary);
        }
        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-size: 1.1em;
            color: var(--text-primary);
            font-weight: bold;
        }
        input[type="text"], input[type="tel"], select {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            border: 1px solid var(--border);
            border-radius: 6px;
            margin-bottom: 8px;
            background: #ffffff;
            color: #000000;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus, input[type="tel"]:focus, select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(77, 171, 247, 0.1);
            background: #ffffff;
            color: #000000;
        }
        .location-row {
            display: flex;
            gap: 8px;
        }
        .location-row input[type="text"] {
            flex: 1;
            margin-bottom: 0;
        }
        .location-row button {
            padding: 8px 12px;
            font-size: 1em;
            border-radius: 6px;
            border: 1px solid var(--accent);
            background: var(--accent);
            color: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .location-row button:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
        }
        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 8px;
        }
        .radio-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-primary);
            font-weight: normal;
            cursor: pointer;
        }
        .radio-group input[type="radio"] {
            margin: 0;
            cursor: pointer;
        }
        .btn {
            width: 100%;
            padding: 14px;
            font-size: 1.2em;
            background-color: var(--accent);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
        }
        .message {
            margin-top: 18px;
            font-size: 1.1em;
            color: var(--text-primary);
        }
        .msg-section {
            background: var(--bg-secondary);
            padding: 30px 30px 20px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 24px var(--shadow);
            max-width: 500px;
            margin: 30px auto 0 auto;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }
        .msg-header {
            font-size: 1.2em;
            color: var(--accent);
            margin-bottom: 18px;
            text-align: center;
        }
        .msg-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .msg-table th, .msg-table td {
            padding: 8px 6px;
            border-bottom: 1px solid var(--border);
            text-align: left;
            color: var(--text-primary);
        }
        .msg-table th {
            background: var(--bg-primary);
            color: var(--text-primary);
        }
        .msg-btn {
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 6px 16px;
            cursor: pointer;
            font-size: 1em;
        }
        .msg-btn:hover {
            background: var(--accent-hover);
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
            background: var(--bg-primary);
            padding: 30px 24px;
            border-radius: 10px;
            box-shadow: 0 4px 24px var(--shadow);
            min-width: 300px;
            max-width: 90vw;
            color: var(--text-primary);
        }
        .popup-content h3 {
            margin-top: 0;
            margin-bottom: 18px;
            color: var(--accent);
        }
        .popup-content textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            border-radius: 6px;
            border: 1px solid var(--border);
            font-size: 1em;
            min-height: 80px;
            resize: vertical;
            background: #ffffff;
            color: #000000;
        }
        .popup-content button {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: none;
            background: var(--accent);
            color: #fff;
            font-size: 1em;
            cursor: pointer;
        }
        .popup-content button:hover {
            background: var(--accent-hover);
        }
        .close-btn {
            background: #dc3545;
            margin-top: 8px;
        }
        .close-btn:hover {
            background: #a71d2a;
        }
        .msg-status {
            color: var(--text-primary);
            text-align: center;
            margin-top: 10px;
        }
        .no-msgs {
            text-align: center;
            color: var(--text-primary);
            margin-top: 10px;
        }
        /* Ensure all text elements are visible */
        * {
            color: inherit;
        }
        /* Force text color on specific elements */
        label, input, select, textarea, button, h1, h2, h3, p, div, span, td, th {
            color: var(--text-primary) !important;
        }
        /* Ensure form elements have proper contrast */
        input::placeholder {
            color: var(--text-secondary);
        }
        /* Force all form labels to be visible */
        .form-group label,
        .radio-group label,
        label[for="name"],
        label[for="phone"],
        label[for="location"],
        label[for="calamity"],
        label[for="need"],
        label[for="blood_group"] {
            color: var(--text-primary) !important;
            font-weight: bold !important;
            text-shadow: 0 0 1px rgba(0,0,0,0.1);
        }
        /* Ensure radio button labels are visible */
        .radio-group label {
            color: var(--text-primary) !important;
            font-weight: bold !important;
        }
        /* Force specific form labels to be black in light mode */
        label[for="name"],
        label[for="phone"],
        label[for="location"],
        label[for="calamity"],
        label[for="need"],
        label[for="blood_group"] {
            color: #000000 !important;
            font-weight: bold !important;
            text-shadow: none;
        }
        /* Force "Are you safe?" label to be black */
        .form-group label:contains("Are you safe?"),
        .form-group label:last-of-type {
            color: #000000 !important;
            font-weight: bold !important;
            text-shadow: none;
        }
        /* Force Yes/No radio button labels to be black */
        .radio-group label {
            color: #000000 !important;
            font-weight: bold !important;
            text-shadow: none;
        }
        /* Force all form text to be black */
        .form-group label,
        .radio-group label,
        label {
            color: #000000 !important;
            font-weight: bold !important;
            text-shadow: none;
        }
        /* Ensure input boxes stay white with black text in dark mode */
        input[type="text"], 
        input[type="tel"], 
        select, 
        textarea {
            background: #ffffff !important;
            color: #000000 !important;
            border: 1px solid #cccccc !important;
        }
        input[type="text"]:focus, 
        input[type="tel"]:focus, 
        select:focus, 
        textarea:focus {
            background: #ffffff !important;
            color: #000000 !important;
            border-color: var(--accent) !important;
        }
        /* Force search input to stay white with black text */
        input[name="msg_search_name"] {
            background: #ffffff !important;
            color: #000000 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Joruri Victim Registration</h2>
        <form method="post" action="victim_registration.php">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <div class="location-row">
                    <input type="text" id="location" name="location" required>
                    <button type="button" onclick="getLocation()">Use My Location</button>
                </div>
            </div>
            <div class="form-group">
                <label for="calamity">Calamity</label>
                <input type="text" id="calamity" name="calamity" required>
            </div>
            <div class="form-group">
                <label for="need">Need</label>
                <input type="text" id="need" name="need" required>
            </div>
            <div class="form-group">
                <label for="blood_group">Blood Group</label>
                <input type="text" id="blood_group" name="blood_group" required>
            </div>
            <div class="form-group">
                <label>Are you safe?</label>
                <div class="radio-group">
                    <label><input type="radio" name="safe" value="yes" required> Yes</label>
                    <label><input type="radio" name="safe" value="no" required> No</label>
                </div>
            </div>
            <button class="btn" type="submit">Submit</button>
        </form>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
    </div>
    <div class="msg-section">
        <div class="msg-header">If you have submitted form before then check your message here</div>
        <form method="post" style="text-align:center; margin-bottom:18px;">
            <input type="text" name="msg_search_name" placeholder="Enter your name" value="<?php echo htmlspecialchars($msg_search_name); ?>" required style="padding:8px; border-radius:6px; border:1px solid var(--border); width:60%; background: #ffffff; color: #000000;">
            <button type="submit" name="msg_search" class="msg-btn">Search</button>
        </form>
        <?php if ($msg_search_name && empty($msg_search_result)): ?>
            <div class="no-msgs">No messages found for your name.</div>
        <?php elseif ($msg_search_result): ?>
            <table class="msg-table">
                <tr>
                    <th>Volunteer</th>
                    <th>Message</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($msg_search_result as $msg): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($msg['volunteer']); ?></td>
                        <td><?php echo htmlspecialchars($msg['vol_message']); ?></td>
                        <td><button class="msg-btn" onclick="openVicMsgPopup('<?php echo $msg['id']; ?>')">Reply</button></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <?php if (!empty($msg_status)): ?>
            <div class="msg-status"><?php echo htmlspecialchars($msg_status); ?></div>
        <?php endif; ?>
    </div>
    <div class="popup" id="vicMsgPopup">
        <div class="popup-content">
            <h3>Send Message to Volunteer</h3>
            <form method="post" action="victim_registration.php">
                <input type="hidden" name="msg_id" id="msg_id">
                <textarea name="vic_message" id="vic_message" placeholder="Type your message..." required></textarea>
                <input type="hidden" name="send_vic_message" value="1">
                <button type="submit">Send</button>
                <button type="button" class="close-btn" onclick="closeVicMsgPopup()">Cancel</button>
            </form>
        </div>
    </div>
    <script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById('location').value =
                        position.coords.latitude + ',' + position.coords.longitude;
                },
                function(error) {
                    alert('Unable to retrieve your location. Please allow location access or enter manually.');
                }
            );
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    }
    function openVicMsgPopup(msgId) {
        document.getElementById('msg_id').value = msgId;
        document.getElementById('vic_message').value = '';
        document.getElementById('vicMsgPopup').style.display = 'flex';
    }
    function closeVicMsgPopup() {
        document.getElementById('vicMsgPopup').style.display = 'none';
    }
    </script>
</body>
</html>
