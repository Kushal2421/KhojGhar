<?php
session_start();
require_once 'db.php';

if (!isset($_POST['message'], $_POST['sender_id'], $_POST['receiver_id'])) {
    die("Invalid request.");
}

$sender_id = intval($_POST['sender_id']);
$receiver_id = intval($_POST['receiver_id']);
$message = trim($_POST['message']);

if ($sender_id === $receiver_id) {
    die("You cannot send messages to yourself.");
}

// ✅ Store message
$query = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}
$stmt->bind_param("iis", $sender_id, $receiver_id, $message);
$stmt->execute();
$stmt->close();

// ✅ Only notify the receiver (not the sender themselves)
if ($sender_id !== $receiver_id) {
    $notification_query = "INSERT INTO notifications (user_id, message) VALUES (?, 'New message received')";
    $notify_stmt = $conn->prepare($notification_query);
    if ($notify_stmt) {
        $notify_stmt->bind_param("i", $receiver_id);
        $notify_stmt->execute();
        $notify_stmt->close();
    }
}

echo "Message sent successfully.";
?>
