<?php
include 'db.php';

$id = $_GET['id'];
$query = "SELECT * FROM properties WHERE id=$id";
$result = mysqli_query($conn, $query);
$property = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    $sql = "UPDATE properties SET title='$title', price='$price', location='$location', description='$description' WHERE id=$id";
    mysqli_query($conn, $sql);
    header("Location: profile.php");
}
?>
<form method="POST">
    <input type="text" name="title" value="<?php echo $property['title']; ?>" required>
    <input type="number" name="price" value="<?php echo $property['price']; ?>" required>
    <input type="text" name="location" value="<?php echo $property['location']; ?>" required>
    <textarea name="description" required><?php echo $property['description']; ?></textarea>
    <button type="submit">Update</button>
</form>
