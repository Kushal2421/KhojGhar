<?php
$conn = new mysqli("localhost", "root", "", "khojghar");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$property_id = isset($_POST['property_id']) ? intval($_POST['property_id']) : 0;
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 1; // Default user ID (change as needed)
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;

if ($property_id === 0 || $rating < 1 || $rating > 5) {
    exit("Invalid data.");
}

// Check if the user has already rated this property
$query = "SELECT id FROM ratings WHERE property_id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $property_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update the rating
    $update_query = "UPDATE ratings SET rating = ? WHERE property_id = ? AND user_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("iii", $rating, $property_id, $user_id);
    $update_stmt->execute();
    echo "Rating updated successfully.";
} else {
    // Insert new rating
    $insert_query = "INSERT INTO ratings (property_id, user_id, rating) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("iii", $property_id, $user_id, $rating);
    $insert_stmt->execute();
    echo "Rating submitted successfully.";
}

$conn->close();
?>
