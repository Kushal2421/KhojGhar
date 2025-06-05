<?php
session_start();
require_once 'db.php';

$logged_in_user_id = $_SESSION['user_id'];
$chat_with_user_id = isset($_GET['receiver_id']) ? intval($_GET['receiver_id']) : 0;

if ($chat_with_user_id === 0) {
    die("Invalid user ID.");
}

// ✅ Fetch messages between the two users, ensuring correct sender names
$query = "
    SELECT messages.*, users.username AS sender_name 
    FROM messages
    JOIN users ON messages.sender_id = users.id
    WHERE (sender_id = ? AND receiver_id = ?) 
       OR (sender_id = ? AND receiver_id = ?)
    ORDER BY messages.created_at ASC";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("iiii", $logged_in_user_id, $chat_with_user_id, $chat_with_user_id, $logged_in_user_id);
$stmt->execute();
$result = $stmt->get_result();

// ✅ Show messages with the correct sender name
while ($msg = $result->fetch_assoc()): ?>
    <div class="message <?= $msg['sender_id'] == $logged_in_user_id ? 'sent' : 'received' ?>">
        <strong><?= ($msg['sender_id'] == $logged_in_user_id) ? 'You' : htmlspecialchars($msg['sender_name']) ?>:</strong>
        <?= htmlspecialchars($msg['message']) ?>
        <br><small><?= date('M d, H:i', strtotime($msg['created_at'])) ?></small>
    </div>
<?php endwhile;

$stmt->close();
?>

    