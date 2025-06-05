<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: register.php');
    exit();
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    $id = isset($_GET['id']) ? "&id=".$_GET['id'] : "";
    header("Location: $page?$id");
    exit();
}
?>
