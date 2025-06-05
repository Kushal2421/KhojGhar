<?php
include('navbar.php');


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get username safely
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KhojGhar - Welcome</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;900&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        body, html {
            height: 100%;
            margin: 0;
        }
        .hero {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('hose.png') center/cover;
            color: white;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
        }
        .hero p {
            font-size: 1.1rem;
            margin-bottom: 20px;
        }
        .btn-glow {
            display: inline-block;
            padding: 12px 24px;
            margin: 10px;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            text-decoration: none;
            color: white;
            background:rgb(254, 152, 0);
            border: 2px solid white;
            border-radius: 8px;
            transition: 0.4s;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.6);
        }
        .btn-glow:hover {
            color: black;
            background: white;
            box-shadow: 0 0 20px rgb(255, 94, 0);
        }
    </style>
</head>
<body>

    <div class="hero">
        <div>
            <h1>  Hey! Welcome,   <span><?= htmlspecialchars($username) ?></span>! 🎉</h1>
            <p>Your journey with KhojGhar starts here!
                <br>
                Wanna Explore Some Houses ?
            </p>
            <div>
                <a href="index.php" class="btn-glow">Home</a>
                <a href="property-list.php" class="btn-glow">Explore Properties</a>
                <a href="profile.php" class="btn-glow">Profile</a>
                <a href="logout.php" class="btn-glow">Logout</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>