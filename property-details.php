<?php
session_start(); // Start session to track views
include 'navbar.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli("localhost", "root", "", "khojghar");
if ($conn->connect_error) {
    die("<h3 class='text-danger text-center mt-3'>Database Connection Failed: " . $conn->connect_error . "</h3>");
}

// Validate and get property ID from URL
$property_id = isset($_GET['property_id']) ? intval($_GET['property_id']) : 0;
if ($property_id === 0) {
    exit("<h3 class='text-danger text-center mt-3'>Error: Invalid property ID.</h3>");
}

// ✅ Prevent duplicate views from the same session
if (!isset($_SESSION['viewed_properties'])) {
    $_SESSION['viewed_properties'] = [];
}

// If the user hasn't viewed this property yet, update the views
if (!in_array($property_id, $_SESSION['viewed_properties'])) {
    $update_query = $conn->prepare("UPDATE properties SET views = views + 1 WHERE id = ?");
    if (!$update_query) {
        die("<h3 class='text-danger text-center mt-3'>View Update Query Failed: " . $conn->error . "</h3>");
    }
    $update_query->bind_param("i", $property_id);
    $update_query->execute();
    $update_query->close();

    // Add this property to the viewed list to prevent duplicate views
    $_SESSION['viewed_properties'][] = $property_id;
}

// Fetch property details
$stmt = $conn->prepare("SELECT * FROM properties WHERE id = ?");
$stmt->bind_param("i", $property_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    exit("<h3 class='text-danger text-center mt-3'>Error: Property Not Found</h3>");
}
$property = $result->fetch_assoc();
$stmt->close();

// Fetch average rating
$rating_stmt = $conn->prepare("SELECT AVG(rating) AS avg_rating FROM property_ratings WHERE property_id = ?");
$rating_stmt->bind_param("i", $property_id);
$rating_stmt->execute();
$rating_result = $rating_stmt->get_result();
$rating_data = $rating_result->fetch_assoc();
$average_rating = round($rating_data['avg_rating'], 1) ?: "No ratings yet"; 
$rating_stmt->close();

$conn->close();

// Function to generate star ratings
function getStars($rating) {
    if ($rating === "No ratings yet") return "<span>No ratings yet</span>";
    
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
    $emptyStars = 5 - ($fullStars + $halfStar);

    return str_repeat("⭐", $fullStars) . ($halfStar ? "⭐️" : "") . str_repeat("☆", $emptyStars);
}

// Validate images array
$images = !empty($property['image']) ? explode(',', $property['image']) : ['default.jpg'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
        }

        .property-container {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            gap: 40px;
            width: 90vw;
            max-width: 1300px;
            margin: auto;
            padding: 20px;
        }

        /* Image Styling */
        .image-container {
            border: 3px solid #ffc107; 
            flex: 1;
            display: flex;
            justify-content: flex-start;
            border-radius: 10px;
        }

        .img-large {
            border: 3px solid #ffc107; 
            width: 100%;
            max-width: 900px;
            height: auto;
            object-fit: cover;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .img-large:hover {
            transform: scale(1.01);
            box-shadow: 0px 0px 15px rgba(255, 193, 7, 0.5);
        }

        .buttons-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            width: 100%;
        }
        .btn {
    width: 100%;
    max-width: 400px;
    padding: 12px;
    font-size: 18px;
    transition: transform 0.3s, box-shadow 0.3s;
    border-radius: 8px;
}
.price {
    font-size: 24px;
    font-weight: bold;
    font-style: italic;
    color: rgb(255, 255, 255); /* White text */
    text-shadow: 0px 0px 12px rgba(0, 255, 13, 0.5); /* Stronger neon glow */
    background: linear-gradient(135deg, #5ec200, #3a8600); /* Smooth gradient */
    padding: 5px 12px;
    border-radius: 8px;
    display: inline-block;
    box-shadow: 0 0 15px rgba(0, 255, 13, 0.3); /* Soft outer glow */
    transition: all 0.3s ease-in-out;
    text-align: center;
    user-select: none; /* Prevent text selection */
}

.price:hover {
    cursor: pointer;
    color: rgb(0, 0, 0); /* Black text */
    background: linear-gradient(135deg, rgb(255, 166, 0), rgb(255, 120, 0)); /* Dynamic gradient shift */
    text-shadow: 0px 0px 15px rgba(255, 255, 255, 0.7); /* Brighter hover glow */
    box-shadow: 0px 0px 20px rgba(255, 166, 0, 0.6); /* Stronger outer glow */
    transform: scale(1.05); /* Subtle hover zoom */
}



/* ULTRA GLOW EFFECT */
.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0px 0px 25px rgba(255, 193, 7, 1), 
                0px 0px 50px rgba(255, 193, 7, 0.7), 
                0px 0px 75px rgba(255, 193, 7, 0.5);
}

/* Different Glow Colors for Each Button */
.btn-primary:hover {
    box-shadow: 0px 0px 25px rgba(0, 123, 255, 1), 
                0px 0px 50px rgba(0, 123, 255, 0.7), 
                0px 0px 75px rgba(0, 123, 255, 0.5);
}

.btn-secondary:hover {
    box-shadow: 0px 0px 25px rgba(108, 117, 125, 1), 
                0px 0px 50px rgba(108, 117, 125, 0.7), 
                0px 0px 75px rgba(108, 117, 125, 0.5);
}

.btn-warning:hover {
    box-shadow: 0px 0px 25px rgba(255, 193, 7, 1), 
                0px 0px 50px rgba(255, 193, 7, 0.7), 
                0px 0px 75px rgba(255, 193, 7, 0.5);
}

.btn-dark:hover {
    box-shadow: 0px 0px 25px rgba(52, 58, 64, 1), 
                0px 0px 50px rgba(52, 58, 64, 0.7), 
                0px 0px 75px rgba(52, 58, 64, 0.5);
}


        .card {
            border-radius: 12px;
            background-color: #fff;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: scale(1.02);
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.2);
        }

        .footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
        /* Title - Make it Bold, Large, and Eye-Catching */
h2 {
    font-size: 32px;
    font-weight: 600;
    color:#333 ;
    text-transform: uppercase;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.16);
    letter-spacing: 1px;
    padding-bottom: 10px;
    border-bottom: 3px solid #ffc107;
    
}
.contact {
    font-weight: 500;
    font-size: 20px; /* Adjust size as needed */
    
    align-items: center;
    gap: 5px;
    text-decoration: none;
    color: #333;
}

.icon {
    width: 20px; /* Make phone icon smaller */
    height: auto;
}

    </style>
</head>
<body>

<div class="property-container">
    <!-- Image -->
    <img src="/khojghar/uploads/<?= htmlspecialchars($images[0]) ?>" 
         alt="Property Image" class="img-large"
         data-bs-toggle="modal" data-bs-target="#imageModal">

    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body text-center p-0"> 
                    <img src="/khojghar/uploads/<?= htmlspecialchars($images[0]) ?>" 
                         alt="Expanded Image" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="buttons-container">
        <a href="chat.php?user_id=<?= htmlspecialchars($property['user_id']) ?>" class="btn btn-primary">📞 Contact Owner</a>
        <a href="property-list.php" class="btn btn-secondary">🏡 Browse Other Properties</a>
        <a href="rate-property.php?property_id=<?= $property_id ?>" class="btn btn-warning">⭐ Rate Property</a>
        <a href="index.php" class="btn btn-dark">🏠 Back to Home</a>
    </div>
</div>

<br>

<!-- Property Details -->
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card p-4 shadow-sm">
            <div class="card-body">
                <h2><?= htmlspecialchars($property['title']) ?></h2>
                <br>
                <p><strong>Owner:</strong> <?= htmlspecialchars($property['name']) ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($property['location']) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($property['description']) ?></p>
                <p><strong>Contact No:</strong> 
                    <a href="tel:<?= htmlspecialchars($property['contact_no']) ?>" class="contact">
                        <img src="phones.png" alt="Phone" class="icon"> <?= htmlspecialchars($property['contact_no']) ?>
                    </a>
                </p>
                <p><strong>Average Rating:</strong> <?= getStars($average_rating) ?> (<?= $average_rating ?>)</p>
                <p class="price">Price: ₹<?= number_format($property['price'], 2) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <p>© 2025 KhojGhar. All Rights Reserved. | Designed with ❤️</p>
</footer>

</body>
</html>
