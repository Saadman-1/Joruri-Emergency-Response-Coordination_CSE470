
<?php
session_start();
if (!isset($_SESSION['darkmode'])) {
	$_SESSION['darkmode'] = false;
}
$isDarkMode = $_SESSION['darkmode'];

// DB connection
include 'db.php';

// Victim search
$victim_search = $_POST['victim_search'] ?? '';
$victims = [];
if ($victim_search) {
	$search = $conn->real_escape_string($victim_search);
	$result = $conn->query("SELECT * FROM victim WHERE name LIKE '%$search%' OR location LIKE '%$search%'");
} else {
	$result = $conn->query("SELECT * FROM victim");
}
while ($row = $result->fetch_assoc()) {
	$victims[] = $row;
}

// Messaging logic
$msg_status = '';
$msg_victim_name = $_POST['msg_victim_name'] ?? '';
$msg_volunteer_name = $_POST['msg_volunteer_name'] ?? '';
$msg_text = $_POST['msg_text'] ?? '';
$msg_search_name = $_POST['msg_search_name'] ?? '';
$victim_messages = [];

// Send message
if (isset($_POST['send_message']) && $msg_victim_name && $msg_volunteer_name && $msg_text) {
	// Check if message row exists
	$stmt = $conn->prepare("SELECT id FROM messages WHERE victim=? AND volunteer=?");
	$stmt->bind_param("ss", $msg_victim_name, $msg_volunteer_name);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		// Update existing message
		$stmt->bind_result($msg_id);
		$stmt->fetch();
		$update = $conn->prepare("UPDATE messages SET vol_message=? WHERE id=?");
		$update->bind_param("si", $msg_text, $msg_id);
		$update->execute();
		$msg_status = "Message sent to victim.";
		$update->close();
	} else {
		// Insert new message
		$insert = $conn->prepare("INSERT INTO messages (victim, volunteer, vol_message) VALUES (?, ?, ?)");
		$insert->bind_param("sss", $msg_victim_name, $msg_volunteer_name, $msg_text);
		$insert->execute();
		$msg_status = "Message sent to victim.";
		$insert->close();
	}
	$stmt->close();
}

// Search victim's messages
if ($msg_search_name) {
	$search = $conn->real_escape_string($msg_search_name);
	$result = $conn->query("SELECT * FROM messages WHERE victim LIKE '%$search%' AND vic_message IS NOT NULL AND vic_message != ''");
	while ($row = $result->fetch_assoc()) {
		$victim_messages[] = $row;
	}
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Track - Joruri</title>
	<link rel="stylesheet" href="style.css">
	<style>
		.main-flex {
			display: flex;
			gap: 40px;
			margin: 40px auto;
			max-width: 1200px;
			justify-content: space-between;
		}
		.left-panel, .right-panel {
			background: var(--bg-secondary);
			border-radius: 12px;
			box-shadow: 0 4px 24px var(--shadow);
			padding: 30px 24px;
			flex: 1;
			min-width: 340px;
			border: 1px solid var(--border);
		}
		.left-panel {
			max-width: 480px;
		}
		.right-panel {
			max-width: 520px;
		}
		h2 {
			color: var(--accent);
			margin-bottom: 18px;
		}
		.search-box {
			margin-bottom: 18px;
		}
		.victim-list {
			max-height: 400px;
			overflow-y: auto;
			margin-bottom: 10px;
		}
		.victim-list table {
			width: 100%;
			border-collapse: collapse;
		}
		.victim-list th, .victim-list td {
			padding: 8px 6px;
			border-bottom: 1px solid var(--border);
			text-align: left;
			color: var(--text-primary);
		}
		.victim-list th {
			background: var(--bg-primary);
			color: var(--accent);
		}
		.msg-form {
			margin-bottom: 24px;
		}
		.msg-status {
			color: var(--accent);
			margin-bottom: 12px;
			font-weight: bold;
		}
		.msg-search-box {
			margin-bottom: 18px;
		}
		.victim-messages {
			max-height: 220px;
			overflow-y: auto;
		}
		.victim-messages table {
			width: 100%;
			border-collapse: collapse;
		}
		.victim-messages th, .victim-messages td {
			padding: 8px 6px;
			border-bottom: 1px solid var(--border);
			text-align: left;
			color: var(--text-primary);
		}
		.victim-messages th {
			background: var(--bg-primary);
			color: var(--accent);
		}
	</style>
</head>
<body data-theme="<?php echo $isDarkMode ? 'dark' : 'light'; ?>">
	<div class="main-flex">
		<div class="left-panel">
			<h2>Victim List</h2>
			<form method="post" class="search-box">
				<input type="text" name="victim_search" placeholder="Search by name or location" value="<?php echo htmlspecialchars($victim_search); ?>" style="padding:8px; border-radius:6px; border:1px solid var(--border); width:70%; background: #fff; color: #000;">
				<button type="submit" style="padding:8px 16px; border-radius:6px; background:var(--accent); color:#fff; border:none;">Search</button>
			</form>
			<div class="victim-list">
				<table>
					<tr>
						<th>Name</th>
						<th>Phone</th>
						<th>Location</th>
						<th>Calamity</th>
						<th>Need</th>
						<th>Blood Group</th>
					</tr>
					<?php foreach ($victims as $v): ?>
					<tr>
						<td><?php echo htmlspecialchars($v['name']); ?></td>
						<td><?php echo htmlspecialchars($v['phone']); ?></td>
						<td><?php echo htmlspecialchars($v['location']); ?></td>
						<td><?php echo htmlspecialchars($v['calamity']); ?></td>
						<td><?php echo htmlspecialchars($v['need']); ?></td>
						<td><?php echo htmlspecialchars($v['blood-group']); ?></td>
					</tr>
					<?php endforeach; ?>
				</table>
			</div>
		</div>
		<div class="right-panel">
			<h2>Message Victim</h2>
			<form method="post" class="msg-form">
				<input type="text" name="msg_victim_name" placeholder="Victim Name" required style="padding:8px; border-radius:6px; border:1px solid var(--border); width:45%; background: #fff; color: #000;">
				<input type="text" name="msg_volunteer_name" placeholder="Your Name (Volunteer)" required style="padding:8px; border-radius:6px; border:1px solid var(--border); width:45%; background: #fff; color: #000;">
				<textarea name="msg_text" placeholder="Type your message..." required style="width:100%;padding:8px;margin-top:8px;border-radius:6px;border:1px solid var(--border);background:#fff;color:#000;"></textarea>
				<button type="submit" name="send_message" style="padding:10px 24px; border-radius:8px; background:var(--accent); color:#fff; border:none; margin-top:8px;">Send Message</button>
			</form>
			<?php if ($msg_status): ?>
				<div class="msg-status"><?php echo htmlspecialchars($msg_status); ?></div>
			<?php endif; ?>
			<form method="post" class="msg-search-box">
				<input type="text" name="msg_search_name" placeholder="Search victim's name for replies" value="<?php echo htmlspecialchars($msg_search_name); ?>" style="padding:8px; border-radius:6px; border:1px solid var(--border); width:70%; background: #fff; color: #000;">
				<button type="submit" style="padding:8px 16px; border-radius:6px; background:var(--accent); color:#fff; border:none;">Search</button>
			</form>
			<div class="victim-messages">
				<?php if ($msg_search_name && empty($victim_messages)): ?>
					<div style="color:var(--text-secondary);">No replies found for this victim.</div>
				<?php elseif ($victim_messages): ?>
					<table>
						<tr>
							<th>Volunteer</th>
							<th>Victim's Reply</th>
						</tr>
						<?php foreach ($victim_messages as $msg): ?>
						<tr>
							<td><?php echo htmlspecialchars($msg['volunteer']); ?></td>
							<td><?php echo htmlspecialchars($msg['vic_message']); ?></td>
						</tr>
						<?php endforeach; ?>
					</table>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const body = document.body;
			setTimeout(() => {
				body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
			}, 100);
		});
	</script>
</body>
</html>
