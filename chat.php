<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$logged_in_user_id = $_SESSION['user_id'];
$chat_with_user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($chat_with_user_id === 0) {
    die("Invalid user ID.");
}

// ✅ Check if the `properties` table exists before querying
$check_table = $conn->query("SHOW TABLES LIKE 'properties'");
if ($check_table->num_rows == 0) {
    die("Error: The 'properties' table does not exist.");
}

// ✅ Prevent User from Messaging Themselves
$property_check = $conn->prepare("SELECT user_id FROM properties WHERE user_id = ?");
if (!$property_check) {
    die("SQL Error: " . $conn->error);
}

$property_check->bind_param("i", $logged_in_user_id);
$property_check->execute();
$property_check->store_result();

if ($property_check->num_rows > 0 && $chat_with_user_id == $logged_in_user_id) {
    die("You cannot message yourself.");
}

$property_check->close();

// Fetch the chat partner's name
$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("i", $chat_with_user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found.");
}

$chat_with_username = htmlspecialchars($user['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        body {
            background: #1a1a1a;
            color: white;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .chat-container {
    max-width: 2400px;
    margin: 40px auto;
    background: #333;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
    display: flex;
    flex-direction: column;
}

.chat-box {
    display: flex;
    flex-direction: column;
    height: 850px;
   
    overflow-y: auto;
    background: #222;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
    scrollbar-width: thin;
}

.message-container {
    display: flex;
    align-items: flex-start;
    width: 100%;
    margin-bottom: 10px;
}

.message {
    padding: 10px 15px;
    border-radius: 12px;
    max-width: 70%;
    word-wrap: break-word;
    font-size: 14px;
    line-height: 1.4;
}

.sent {
    background: #ffae00;
    width: 200px;
    margin-bottom:10px;
    color: black;
    align-self: flex-end;
    text-align: right;
}

.received {
    width:200px;
    background: #007bff;
    color: white;
    align-self: flex-start;
}

.input-area {
    display: flex;
    align-items: center;
    gap: 10px;
}

textarea {
    flex: 1;
    resize: none;
    height: 40px;
    padding: 10px;
    font-size: 14px;
}

.btn-send {
    min-width: 80px;
    font-weight: bold;
}

.chat-box-header {
    background: #222;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 15px;
}


    </style>
</head>
<body>

<div class="chat-container">
    <!-- Back to Home Button -->
    <a href="index.php" class="btn btn-light back-home">🏠 Back to Home</a>
<br>
    <!-- Chat Header Inside the Box -->
    <div class="chat-box-header">
        <h2>Chat with <?= htmlspecialchars($chat_with_username) ?> 🙍🏻‍♂️</h2>
    </div>

    <div class="chat-box" id="chat-box"></div>

    <!-- Message Form -->
    <form id="chatForm" class="input-area">
        <input type="hidden" name="sender_id" value="<?= $logged_in_user_id ?>">
        <input type="hidden" name="receiver_id" value="<?= $chat_with_user_id ?>">
        <textarea name="message" id="message" class="form-control" placeholder="Type your message..." required></textarea>
        <button type="submit" class="btn btn-warning btn-send">Send</button>
    </form>
</div>


<script>
    function fetchMessages() {
        let chatBox = document.getElementById("chat-box");
        let receiver_id = <?= $chat_with_user_id ?>;
        
        fetch("fetch_messages.php?receiver_id=" + receiver_id)
            .then(response => response.text())
            .then(data => {
                chatBox.innerHTML = data;
                chatBox.scrollTop = chatBox.scrollHeight; // Smooth scroll to bottom
            })
            .catch(error => console.error("Error fetching messages:", error));
    }

    document.getElementById("chatForm").onsubmit = function(event) {
        event.preventDefault();
        
        let formData = new FormData(document.getElementById("chatForm"));

        fetch("send_message.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.includes("Message sent successfully")) {
                document.getElementById("message").value = "";
                fetchMessages(); // ✅ Instantly refresh messages
            } else {
                alert("Error: " + data);
            }
        })
        .catch(error => console.error("Error:", error));
    };

    setInterval(fetchMessages, 2000); // ✅ Faster auto-refresh (every 2 seconds)
    fetchMessages(); // ✅ Load messages on page load
</script>

</body>
</html>
