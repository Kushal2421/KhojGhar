<?php
include 'navbar.php';

// Start session only if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "khojghar");

// Check for errors
if ($conn->connect_error) {
    die("<h3>Connection failed: " . $conn->connect_error . "</h3>");
}

// Get property_id and logged-in user_id
if (!isset($_GET['property_id'])) {
    die("<h3>Error: Property ID is missing.</h3>");
}

$property_id = intval($_GET['property_id']);
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

if ($user_id === 0) {
    die("<h3>Error: You must be logged in to chat.</h3>");
}

// Get property owner ID
$owner_id_query = $conn->prepare("SELECT user_id FROM properties WHERE id = ?");
$owner_id_query->bind_param("i", $property_id);
$owner_id_query->execute();
$result = $owner_id_query->get_result();

if ($result->num_rows === 0) {
    die("<h3>Error: Property not found.</h3>");
}

$owner = $result->fetch_assoc();
$owner_id = $owner['user_id'];
$owner_id_query->close();

// Fetch chat messages
$chat_query = $conn->prepare("SELECT * FROM messages WHERE 
    (sender_id = ? AND receiver_id = ?) OR 
    (sender_id = ? AND receiver_id = ?) 
    ORDER BY created_at ASC");
$chat_query->bind_param("iiii", $user_id, $owner_id, $owner_id, $user_id);
$chat_query->execute();
$chat_result = $chat_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with Owner</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="chat-container">
        <h2>CHAT WITH OWNER</h2>

        <div class="chat-box" id="chat-box">
            <?php while ($chat = $chat_result->fetch_assoc()) { ?>
                <p><strong><?php echo ($chat['sender_id'] == $user_id) ? "You" : "Owner"; ?>:</strong> <?php echo htmlspecialchars($chat['message']); ?></p>
            <?php } ?>
        </div>

        <form id="chatForm">
            <input type="hidden" name="sender_id" value="<?php echo $user_id; ?>">
            <input type="hidden" name="receiver_id" value="<?php echo $owner_id; ?>">
            <input type="hidden" name="property_id" value="<?php echo $property_id; ?>">
            <input type="text" id="message" name="message" placeholder="Type your message..." required>
            <button type="submit">Send</button>
        </form>
    </div>

    <script>
      document.getElementById("chatForm").onsubmit = function(event) {
    event.preventDefault();
    let formData = new FormData(document.getElementById("chatForm"));

    fetch("send_message.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); // Debugging - check response from PHP
        if (data.includes("Message sent successfully")) {
            document.getElementById("chat-box").innerHTML += `<p><strong>You:</strong> ${formData.get("message")}</p>`;
            document.getElementById("message").value = "";
        } else {
            alert("Error: " + data);
        }
    })
    .catch(error => console.error("Fetch error:", error));
};

    </script>

</body>
</html>
