<?php
require_once 'db.php'; // Ensure database connection

session_start();
if (!isset($_SESSION['user_id'])) {     
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user ID

// Fetch user data
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Error: User not found in database.");
}

$stmt->close(); // Close statement after fetching user data

// ✅ Fetch only received messages (correct sender name shown)
$inbox_sql = "SELECT m.*, u.username AS sender_name 
              FROM messages m 
              JOIN users u ON m.sender_id = u.id 
              WHERE m.receiver_id = ? 
              ORDER BY m.created_at DESC";

$inbox_stmt = $conn->prepare($inbox_sql);
if (!$inbox_stmt) {
    die("SQL Error: " . $conn->error);
}

$inbox_stmt->bind_param("i", $user_id);
$inbox_stmt->execute();
$inbox_result = $inbox_stmt->get_result();
$inbox_stmt->close(); // Close after fetching messages

$conn->close(); // Close database connection
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KhojGhar Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
     body {
    background: linear-gradient(135deg, #ffae00, #d48c00, #b37400);
    color: white;
    text-align: center;


}

        .container {
            max-width: 800px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
            margin-top: 50px;
        }
        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid white;
            margin-bottom: 15px;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
        }
        .btn {
            font-size: 18px;
            padding: 12px 20px;
            border-radius: 30px;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(255, 255, 255, 0.2);
        }
        .btn:hover {
            transform: scale(1.05);
        }
        .inbox-item {
            background: rgba(255, 255, 255, 0.2);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: 0.3s;
        }
        .inbox-item:hover {
            background: rgba(255, 255, 255, 0.4);
        }

    .footer {
    background-color: #000000;
    color: #ffffff;
    padding: 30px 20px;
    font-family: 'Calibri', sans-serif;
}

.footer-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #333;
}

.footer-section h3 {
    font-size: 20px;
    margin-bottom: 10px;
    color: #ffae00;
}

.footer-section p,
.footer-section ul {
    font-size: 14px;
    line-height: 1.6;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin-bottom: 8px;
}

.footer-section ul li a {
    text-decoration: none;
    color: #ffae00;
    transition: color 0.3s ease;
    font-weight: bold;
}

.footer-section ul li a:hover {
    color: #ffffff;
    text-decoration: underline;
}
.newsletter-form input {
    width: 70%;
    padding: 10px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    outline: none;
}

.newsletter-form button {
    background: #ffae00;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    color: black;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
    box-shadow: 0px 4px 10px rgba(255, 174, 0, 0.5);
}

.newsletter-form button:hover {
    background: #d48c00;
    box-shadow: 0px 4px 15px rgba(255, 174, 0, 0.8);
}


    </style>
</head>
<body>

<div class="container text-center">
    <h1>🎉 Welcome To KhojGhar, <?= htmlspecialchars($user['username']) ?>!</h1>
<br>
    <!-- Profile Picture Section -->
    <?php if (!empty($user['profile_pic'])): ?>
        <img src="uploads/<?= htmlspecialchars($user['profile_pic']) ?>" alt="Profile Picture" class="profile-img">

    <?php else: ?>
        <p>No profile picture uploaded.</p>
    <?php endif; ?>

    <!-- User Info -->
    <h2><?= htmlspecialchars($user['username']) ?></h2>
    <p><strong>Your Name:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>


    <!-- Profile Picture Upload -->
    <form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="profile_pic" class="form-control my-2" required>
    <button type="submit" class="btn btn-warning">Upload Profile Picture</button>
</form>

    <!-- Navigation Buttons -->
    <div class="mt-4">
        <a href="index.php" class="btn btn-primary">🏠 Home</a>
        <a href="admin-dashboard.php" class="btn btn-success">📂 Your Property</a>
        <a href="logout.php" class="btn btn-danger">🚪 Logout</a>
    </div>
<br>
    <!-- 📩 Inbox Section -->
    <!-- 📩 Inbox Section -->
<h3 class="mt-4">📩 Your Inbox</h3>
<div class="mt-3">
    <?php if ($inbox_result && $inbox_result->num_rows > 0): ?>
        <?php while ($message = $inbox_result->fetch_assoc()): ?>
            <div class="inbox-item">
                <a href="chat.php?user_id=<?= $message['sender_id'] ?>" class="text-white text-decoration-none">
                    <strong><?= htmlspecialchars($message['sender_name']) ?></strong>
                    <p><?= htmlspecialchars(substr($message['message'], 0, 30)) ?>...</p>
                    <small class="text-muted"><?= date('M d, H:i', strtotime($message['created_at'])) ?></small>
                </a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No new messages.</p>
    <?php endif; ?>
</div>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<footer class="footer">
    <div class="footer-container">
        <div class="footer-section about">
            <h3>About Us</h3>
            <p>Your trusted partner in finding and selling properties with ease and confidence.</p>
        </div>

        <div class="footer-section contact">
            <h3>Contact Us</h3>
            <p>📞 Phone: +919892991284</p>
            <p>✉️ Email: support@khojghar.com</p>
        </div>

        <div class="footer-section quick-links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="privacy.php">Privacy Policy</a></li>
                <li><a href="terms.php">Terms & Conditions</a></li>
            </ul>
        </div>

        <div class="footer-section newsletter">
            <h3>Subscribe to Our Newsletter</h3>
            <form class="newsletter-form">
                <input type="email" placeholder="Enter your email" required>
                <button type="submit">Subscribe</button>
            </form>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2024 KhojGhar. All Rights Reserved.</p>
    </div>
</footer>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
