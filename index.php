<?php
session_start();
include('navbar.php');
include('db.php');

// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']);

// Check if property was successfully added
$property_added_message = $_SESSION['property_added'] ?? null;
unset($_SESSION['property_added']); // Clear session flag after displaying the message
?>

<!DOCTYPE html>
<html>
<head>
    <title>KhojGhar - Home</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .intro-section {
            background-image: url('path-to-your-image.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 100px 20px;
            border-radius: 8px;
        }

        .hero-buttons .button {
            padding: 10px 20px;
            font-size: 16px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px;
            transition: all 0.3s ease;
        }

        .get-started {
            background-color: #28a745;
            color: white;
        }

        .sell-property {
            background-color: #007bff;
            color: white;
        }

        .hero-buttons .button:hover {
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.8);
        }

        .success-message {
            background-color: #28a745;
            color: white;
            padding: 10px;
            margin: 20px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="intro-section">
    <h1>Welcome to KhojGhar</h1>
    <p>A place to find a home of your own</p>

    <div class="hero-buttons">
        <!-- Get Started Button - Redirects based on login status -->
        <a href="<?php echo $is_logged_in ? 'profile.php' : 'register.php'; ?>" class="button get-started">
            <?php echo $is_logged_in ? 'Go to Profile' : 'Get Started'; ?>
        </a>

        <!-- Sell Property Button -->
        <a href="add-property.php" class="button sell-property">Sell Property</a>
    </div>
</div>

<!-- Success Message for Property Added -->
<?php if ($property_added_message): ?>
    <div class="success-message">
        <p><?php echo htmlspecialchars($property_added_message); ?></p>
    </div>
<?php endif; ?>
<br>
<h2><strong>Browse Recent Properties</strong></h2>
<div class="property-grid">
    <?php
    $result = $conn->query("SELECT * FROM properties");
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='property-card'>";
            echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
            echo "<p>Price: " . htmlspecialchars($row['price']) . " INR</p>";
            echo "<a href='property-details.php?property_id=" . htmlspecialchars($row['id']) . "' class='details-btn'>View Details</a>";
            echo "</div>";
        }
    } else {
        echo "<p>No properties available.</p>";
    }
    ?>
</div>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
