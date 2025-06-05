<?php
session_start();
$conn = new mysqli("localhost", "root", "", "khojghar");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get property ID
$property_id = intval($_GET['property_id'] ?? 0);
if ($property_id <= 0) {
    die("Invalid property ID.");
}

// Check if rating already exists
$query = "SELECT id, rating FROM property_ratings WHERE property_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $property_id);
$stmt->execute();
$stmt->store_result();
$userHasRated = $stmt->num_rows > 0;
$stmt->bind_result($existing_id, $existing_rating);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rating'])) {
    $rating = intval($_POST['rating']);

    if ($userHasRated) {
        $stmt = $conn->prepare("UPDATE property_ratings SET rating = ? WHERE id = ?");
        $stmt->bind_param("ii", $rating, $existing_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO property_ratings (property_id, rating) VALUES (?, ?)");
        $stmt->bind_param("ii", $property_id, $rating);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Rating submitted successfully!'); window.location.href='rate_property.php?property_id=$property_id';</script>";
    } else {
        echo "<script>alert('Error submitting rating: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Fetch updated average rating
$avg_rating = 0;
$count = 0;
$query = "SELECT AVG(rating) AS avg_rating, COUNT(*) AS count FROM property_ratings WHERE property_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $property_id);
$stmt->execute();
$stmt->bind_result($avg_rating, $count);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Rate Property</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .rating-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .stars span {
            font-size: 30px;
            cursor: pointer;
            color: #ccc;
        }
        .stars span.selected {
            color: gold;
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="container mt-5 d-flex justify-content-center">
    <div class="rating-container text-center">
        <h3>Average Rating: <?= round($avg_rating, 2) ?> (<?= $count ?> votes)</h3>
        
        <?php if ($userHasRated): ?>
            <p style="color: blue;">You already rated this property: <?= $existing_rating ?> stars</p>
        <?php else: ?>
            <form method="POST">
                <label for="rating" class="d-block">Rate this property:</label>
                <div class="stars" id="starRating">
                    <span data-value="1">&#9733;</span>
                    <span data-value="2">&#9733;</span>
                    <span data-value="3">&#9733;</span>
                    <span data-value="4">&#9733;</span>
                    <span data-value="5">&#9733;</span>
                </div>
                <input type="hidden" name="rating" id="rating" required>
                <button type="submit" class="btn btn-primary mt-3">Submit Rating</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const stars = document.querySelectorAll(".stars span");
        const ratingInput = document.getElementById("rating");

        stars.forEach(star => {
            star.addEventListener("click", function () {
                let value = this.getAttribute("data-value");
                ratingInput.value = value;
                stars.forEach(s => s.classList.remove("selected"));
                for (let i = 0; i < value; i++) {
                    stars[i].classList.add("selected");
                }
            });
        });
    });
</script>

</body>
</html>