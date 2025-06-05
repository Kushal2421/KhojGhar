<?php
include 'db.php'; // Database connection

$sort_type = $_GET['sort_type'] ?? '';
$sort_district = $_GET['sort_district'] ?? '';
$sort_property_type = $_GET['sort_property_type'] ?? '';
$search_query = $_GET['search_query'] ?? '';

$sql = "SELECT * FROM properties WHERE 1";

// Filtering based on BHK type
$sort_type = $_GET['sort_type'] ?? '';
$sort_district = $_GET['sort_district'] ?? '';

$sql = "SELECT * FROM properties WHERE description LIKE '%$sort_type%' AND district LIKE '%$sort_district%'";

// Filtering by district
if (!empty($sort_district)) {
    $sql .= " AND district LIKE '%" . mysqli_real_escape_string($conn, $sort_district) . "%'";
}

// Filtering by property type (Commercial/Residential)
if (!empty($sort_property_type)) {
    $sql .= " AND Property_type = '" . mysqli_real_escape_string($conn, $sort_property_type) . "'";
}

// Searching by title or location
if (!empty($search_query)) {
    $sql .= " AND (title LIKE '%" . mysqli_real_escape_string($conn, $search_query) . "%' OR location LIKE '%" . mysqli_real_escape_string($conn, $search_query) . "%')";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        
        h2 {
            color: #ffffff;
            text-align: center;
            margin: 30px 0;
            text-transform: uppercase;
            font-weight: bold;
        }
        .container{
            margin-left:50px;
        }
        .property-container {
            margin-left:20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .property-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
            background: white;
            cursor: pointer;
        }
        .property-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }
        .property-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .property-details {
            text-align: center;
            padding: 10px;
        }
        .property-details h3 {
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: bold;
        }
        .property-details p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }
        .price {
            color: red;
            font-size: 16px;
            font-weight: bold;
        }
        .btn-custom {
            width: 100%;
            margin-top: 10px;
            background: linear-gradient(135deg, #ff7b00, #ffcc00);
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
        }
        .btn-custom:hover {
            background: linear-gradient(135deg, #ffcc00, #ff7b00);
            box-shadow: 0px 0px 15px rgba(255, 204, 0, 0.8);
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>
<br>
<h2>🏡 Property Listings</h2>

<div class="container mb-3">
    <form method="GET" class="d-flex gap-2">
        <input type="text" name="search_query" placeholder="Search properties..." class="form-control" value="<?php echo htmlspecialchars($search_query); ?>">

        <select name="sort_type" class="form-select">
            <option value="">Sort by BHK Type</option>
            <option value="1BHK" <?php if($sort_type == '1BHK') echo 'selected'; ?>>1BHK</option>
            <option value="2BHK" <?php if($sort_type == '2BHK') echo 'selected'; ?>>2BHK</option>
            <option value="3BHK" <?php if($sort_type == '3BHK') echo 'selected'; ?>>3BHK</option>
            <option value="4BHK" <?php if($sort_type == '4BHK') echo 'selected'; ?>>4BHK</option>
        </select>

        <select name="sort_district" class="form-select">
            <option value="">Sort by District</option>
            <option value="Pune" <?php if($sort_district == 'Pune') echo 'selected'; ?>>Pune</option>
            <option value="Thane" <?php if($sort_district == 'Thane') echo 'selected'; ?>>Thane</option>
            <option value="Mumbai" <?php if($sort_district == 'Mumbai') echo 'selected'; ?>>Mumbai</option>
            <option value="Nagpur" <?php if($sort_district == 'Nagpur') echo 'selected'; ?>>Nagpur</option>
            <option value="Nashik" <?php if($sort_district == 'Nashik') echo 'selected'; ?>>Nashik</option>
        </select>

        <select name="sort_property_type" class="form-select">
            <option value="">Sort by Property Type</option>
            <option value="Commercial" <?php if($sort_property_type == 'Commercial') echo 'selected'; ?>>Commercial</option>
            <option value="Residential" <?php if($sort_property_type == 'Residential') echo 'selected'; ?>>Residential</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
        <button type="button" class="btn btn-secondary" onclick="window.location.href='property-list.php'">Reset Filters</button>
    </form>
</div>


<div class="property-container">
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="property-card" onclick="window.location.href='property-details.php?property_id=<?php echo $row['id']; ?>'">
            <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" 
                alt="Property Image" 
                class="property-image" 
                loading="lazy"
                onerror="this.onerror=null; this.src='img/default-image.jpg';">

            <div class="property-details">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                <p class="price"><strong>Price: ₹<?php echo number_format($row['price'], 2); ?></strong></p>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($row['property_type']); ?></p>
                <a href="property-details.php?property_id=<?php echo $row['id']; ?>" class="btn btn-custom">View Details</a>
            </div>
        </div>
    <?php } ?>
</div>

</body>
</html>
