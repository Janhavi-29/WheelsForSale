<?php
include('connection.php');
session_start(); // Make sure the session is started

// Get the category from the URL (e.g., SUV, Sedan, etc.)
$category = isset($_GET['category']) ? $_GET['category'] : 'SUV'; // Default to SUV if no category is passed

// Query to get cars from the selected category
$sql = "SELECT * FROM cars WHERE category = '$category'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category); ?> Cars</title>
    
    <!-- Add External or Inline CSS for Styling -->
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 30px;
            font-size: 2.5rem;
            color: #333;
        }

        .car-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        /* Car card styles */
        .car-card {
            width: 300px; /* Adjusted width for a rectangular card */
            height: 450px; /* Height is greater than width for rectangle look */
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
        }

        .car-card:hover {
            transform: translateY(-5px); /* Lift effect on hover */
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.2);
        }

        .car-card img {
            width: 100%;
            height: 60%; /* Image takes up more height */
            object-fit: cover;
            border-radius: 5px;
        }

        .car-card h3 {
            font-size: 1.3rem;
            color: #333;
            margin: 10px 0;
        }

        .car-card p {
            font-size: 1rem;
            color: #888;
            margin: 0 0 10px;
        }

        .buy-btn {
            background-color: #e50914;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .buy-btn:hover {
            background-color: #f40612;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .car-list {
                gap: 15px;
            }

            .car-card {
                width: 45%;
            }
        }

        @media (max-width: 480px) {
            .car-card {
                width: 90%;
                height: 350px; /* Reduce height for smaller screens */
            }
        }
    </style>
</head>
<body>
    <h1><?php echo htmlspecialchars($category); ?> Cars</h1>

    <div class="car-list">
        <?php
        // Check if there are cars for this category
        if (mysqli_num_rows($result) > 0) {
            // Display each car as a card
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="car-card">';
                echo '<img src="assets/images/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p>Price: $' . htmlspecialchars($row['price']) . '</p>';
                echo '<p>Model Year: ' . htmlspecialchars($row['model_year']) . '</p>';  // Added model year
                // Link to the new_payment.php page with car ID passed in the URL
                echo '<a href="new_payment.php?id=' . $row['id'] . '"><button class="buy-btn">Buy Now</button></a>';
                echo '</div>';
            }
        } else {
            echo '<p>No cars available in this category.</p>';
        }
        ?>
    </div>
</body>
</html>
