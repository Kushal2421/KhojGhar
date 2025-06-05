<?php
include 'db.php';
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM properties WHERE id=$id");
header("Location: profile.php");
?>
<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: register.php');
    exit();
}

if (isset($_GET['id'])) {
    $property_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Ensure the property belongs to the logged-in user before deleting
    $delete_query = $conn->prepare("DELETE FROM properties WHERE id = ? AND owner_id = ?");
    $delete_query->bind_param("ii", $property_id, $user_id);
    $delete_query->execute();

    header("Location: admin-dashboard.php");
    exit();
} else {
    header("Location: admin-dashboard.php");
    exit();
}
?>
