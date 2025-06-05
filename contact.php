<?php include('navbar.php'); ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    $to = "your_email@example.com"; // Replace with your actual email
    $headers = "From: " . $email;
    $fullMessage = "Name: $name\nEmail: $email\nMessage: $message";

    if (mail($to, $subject, $fullMessage, $headers)) {
        echo "<script>showAlert('success', 'Message sent successfully!');</script>";
    } else {
        echo "<script>showAlert('danger', 'Message sending failed. Try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Contact Us - KhojGhar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
   
    .contact-form {
        width: 100vw;
        height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .contact-form h2 {
        color: orange;
        font-size: 3rem;
        margin-bottom: 20px;
    }
    .form-group {
        width: 50%;
        margin-bottom: 20px;
    }
    .form-control {
        width: 100%;
        height: 60px; /* Increased height */
        font-size: 1.5rem; /* Bigger text */
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 2px solid orange;
        padding: 15px;
        border-radius: 10px;
    }
    textarea.form-control {
        height: 150px; /* Bigger textarea */
    }
    .btn-submit {
        width: 50%;
        height: 60px;
        font-size: 1.5rem;
        background: #007bff;
        border: none;
        transition: 0.3s;
    }
    .btn-submit:hover {
        background: #0056b3;
    }
</style>

</head>
<body>

<div class="contact-form">
    <h2>Contact Us</h2>
    <p>We'd love to hear from you! Fill out the form below.</p>

    <div id="alertBox"></div> <!-- Alerts will show here -->

    <form id="contactForm" method="POST">
        <div class="form-group">
            <input type="text" class="form-control" name="name" id="name" placeholder="Your Name" required>
        </div>
        <div class="form-group">
            <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
        </div>
        <div class="form-group">
            <textarea class="form-control" name="message" id="message" rows="5" placeholder="Your Message" required></textarea>
        </div>
        <button type="submit" class="btn btn-submit">Send Message</button>
    </form>
</div>

<script>
    // JavaScript validation
    $("#contactForm").on("submit", function (e) {
        let name = $("#name").val().trim();
        let email = $("#email").val().trim();
        let subject = $("#subject").val().trim();
        let message = $("#message").val().trim();

        if (!name || !email || !subject || !message) {
            showAlert("warning", "All fields are required!");
            e.preventDefault();
        }
    });

    // Alert function
    function showAlert(type, message) {
        $("#alertBox").html(`
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
    }
</script>

</body>
</html>
