<?php
include 'db.php';

$seller_id = $_GET['seller_id'];
$query = "SELECT * FROM users WHERE id=$seller_id";
$result = mysqli_query($conn, $query);
$seller = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contact Seller</title>
</head>
<body>
    <h2>Contact <?php echo $seller['name']; ?></h2>
    <p>Email: <?php echo $seller['email']; ?></p>
    <p>Phone: <?php echo $seller['phone']; ?></p>
</body>
</html>
