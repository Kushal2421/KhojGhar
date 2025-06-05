<?php
session_start();
include('navbar.php');
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: register.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch properties uploaded by the user
$property_query = $conn->prepare("SELECT * FROM properties WHERE user_id = ?");
if (!$property_query) {
    die("Property Query Preparation Failed: " . $conn->error);
}
$property_query->bind_param("i", $user_id);
$property_query->execute();
$properties = $property_query->get_result();

// Fetch total views on all properties uploaded by the user
$views_query = $conn->prepare("SELECT SUM(views) AS total_views FROM properties WHERE user_id = ?");
if (!$views_query) {
    die("Views Query Preparation Failed: " . $conn->error);
}
$views_query->bind_param("i", $user_id);
$views_query->execute();
$views_result = $views_query->get_result()->fetch_assoc();
$total_views = $views_result['total_views'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
  

.container {
    
    width: 90%;
    max-width: 1200px; /* Limit max width */
}

.dashboard-container {
    
    margin-top:50px;
    padding: 30px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    width: 100%;
}

        .property-card {
            transition: transform 0.2s ease-in-out;
            text-align: center;
        }
        .property-card:hover {
            transform: scale(1.05);
        }
        .property-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="dashboard-container p-4">
        <h2 class="text-center mb-4">📊 Admin Dashboard</h2>
        <div class="alert alert-info text-center">
            <strong>Total Property Views:</strong> <?= $total_views ?> 👀
        </div>

        <h3 class="mb-3">Your Properties</h3>
        <div class="row">
            <?php if ($properties->num_rows > 0): ?>
                <?php while ($property = $properties->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="card property-card mb-4">
                            <img src="uploads/<?= htmlspecialchars($property['image']) ?>" alt="Property Image" class="property-image">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="property-details.php?id=<?= $property['id'] ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($property['title']) ?>
                                    </a>
                                </h5>
                                <p class="card-text text-muted">Views: <?= $property['views'] ?> 👀</p>
                                <button class="btn btn-danger btn-sm" onclick="deleteProperty(<?= $property['id'] ?>)">Delete</button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No properties uploaded yet.</p>
            <?php endif; ?>
        </div>

        <a href="profile.php" class="btn btn-success w-100 mt-3">⬅ Back to Profile</a>
    </div>
</div>

<script>
    function deleteProperty(propertyId) {
        if (confirm("Are you sure you want to delete this property?")) {
            window.location.href = "delete-property.php?id=" + propertyId;
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
