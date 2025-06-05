<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $user_id = $_SESSION['user_id'];
    $file_name = basename($_FILES['profile_pic']['name']);
    $target_file = "uploads/" . $file_name;

    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
        $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
        $stmt->bind_param("si", $file_name, $user_id);
        $stmt->execute();
        echo "Profile picture uploaded successfully.";
        header("Location: profile.php");
        exit();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
} else {
    echo "Invalid request.";
}
?>
