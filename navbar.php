<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} // Start the session to check login status
?>
<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <style>
    /* Glow effect on navbar links */
    .nav-link {
      position: relative;
      padding:51px;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-weight: bold;
      color: #ffffff;
      transition: all 0.3s ease-in-out;
    }

    .nav-link:hover {
      color: #ffcc00; /* Yellow color on hover */
      text-shadow: 0 0 10px rgba(255, 204, 0, 1), 0 0 20px rgba(255, 204, 0, 1), 0 0 30px rgba(255, 204, 0, 1); /* Glow effect */
    }

    /* Custom styling for the navbar */
    .navbar {
      background-color: #2a2a2a; /* Dark background */
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    .navbar .logo {
      color: #ffffff;
      font-size: 24px;
      font-weight: bold;
    }
    .navbar .logo:hover {
      color:rgb(255, 153, 0);
      font-size: 24px;
      font-weight: bold;
    }

    .navbar .nav-links {
      margin-left: auto;
    }

    .navbar .nav-links li {
      list-style: none;
      display: inline-block;
      margin-left: 20px;
    }

    .navbar .nav-links li a {
      text-decoration: none;
      color: #ffffff;
      font-size: 16px;
      padding: 10px;
    }

    .sell-property-btn {
      background-color: #ff6600; /* Orange color for sell button */
      padding: 10px 20px;
      border-radius: 5px;
      text-transform: uppercase;
      font-weight: bold;
      color: white;
      transition: all 0.3s ease-in-out;
    }

    .sell-property-btn:hover {
      background-color: #ff9900;
      box-shadow: 0 0 10px rgba(255, 153, 0, 0.8);
    }

  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a href="index.php" class="navbar-brand logo">KhojGhar</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto nav-links">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="property-list.php">Properties</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact.php">Contact</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="profile.php">Profile</a>
          </li>
          <li class="nav-item">
            <a href="<?php echo isset($_SESSION['user_id']) ? 'add-property.php' : 'login.php'; ?>" class="nav-link sell-property-btn">Sell Property</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
