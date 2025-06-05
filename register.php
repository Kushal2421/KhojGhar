<?php
session_start();
include('db.php');


// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle Registration
if (isset($_POST['register'])) {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['username'] = $username;
        header('Location: welcome.php');
        exit();
    } else {
        $register_error = "Error: " . $stmt->error;
    }
}

// Handle Login
if (isset($_POST['login'])) {
    $email = $_POST['login_email'] ?? '';
    $password = $_POST['login_password'] ?? '';

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: welcome.php');
        exit();
    } else {
        $login_error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register or Login</title>
    <style>
/* CSS for Register and Login Side-by-Side with Centered Heading */
/* Basic Reset */
/* Basic Reset */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html, body {
    height: 100%;
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    display: flex;
    flex-direction: column;
}

/* Center Heading */
h1 {
    text-align: center;
    font-size: 50px;
    margin: 20px 0;
    font-weight: bold;
}

/* Center Forms */
.container {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 40px;
    padding-bottom: 20px;
}

/* Equal Sized Form Boxes */
.form-box {
    border: 2px solid #ccc;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    background-color: #fff;
    width: 360px;
    height: 360px; /* Make both forms the same height */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* Inputs */
input {
    width: calc(100% - 20px);
    padding: 12px;
    margin-bottom: 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    background-color: #e8f0fe;
}

/* Buttons */
button {
    width: 100%;
    padding: 12px;
    background-color: #4CAF50;
    color: white;
    font-size: 16px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #45a049;
}

/* Footer */
footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 15px;
    font-size: 14px;
    width: 100%;
}

footer a {
    color: #ddd;
    text-decoration: none;
    margin: 0 10px;
    transition: color 0.3s;
}

footer a:hover {
    color: #fff;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .container {
        flex-direction: column;
        align-items: center;
    }
    .form-box {
        width: 90%;
        height: auto;
    }
}



    </style>
</head>
<body>

<h1>Welcome to KhojGhar 🎉</h1>

<div class="container">

    <!-- Registration Form -->
    <div class="form-box">
        <h2>Register</h2>
        <?php if (isset($register_error)) echo "<p class='error'>$register_error</p>"; ?>
        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" required>
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit" name="register">Register</button>
        </form>
    </div>

    <!-- Login Form -->
    <div class="form-box">
        <h2>Already a Customer? Login</h2>
        <?php if (isset($login_error)) echo "<p class='error'>$login_error</p>"; ?>
        <form method="POST">
            <label>Email:</label>
            <input type="email" name="login_email" required>
            <label>Password:</label>
            <input type="password" name="login_password" required>
            <button type="submit" name="login">Login</button>
        </form>
    </div>

</div>
<footer>
   
    <p>&copy; <?php echo date("Y"); ?> KhojGhar. All Rights Reserved.</p>
    <p>
        <a href="about.php" style="color: #ddd; text-decoration: none; margin: 0 10px;">About Us</a> |
        <a href="contact.php" style="color: #ddd; text-decoration: none; margin: 0 10px;">Contact</a> |
        <a href="privacy.php" style="color: #ddd; text-decoration: none; margin: 0 10px;">Privacy Policy</a>
    </p>
</footer>

</body>
</html>
