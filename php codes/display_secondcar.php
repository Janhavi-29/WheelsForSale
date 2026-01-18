<?php0
include('connection.php');

// Fetch all second-hand cars from the database
$sql = "SELECT * FROM second_hand_cars";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Second Hand Cars</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file here -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for AJAX -->
</head>
<body>

    <div class="content">
        <h1>Second Hand Cars</h1>

        <!-- Dynamically display cars as cards -->
        <div class="car-cards-container" id="carList">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Ensure the image path is correctly formed
                    $imagePath = isset($row['image']) && !empty($row['image']) ? 'assets/images/' . $row['image'] : 'assets/images/default.jpg';

                    echo "<div class='car-card'>
                            <div class='car-image-container'>
                                <img src='$imagePath' alt='Car Image' class='car-image'>
                            </div>
                            <div class='car-details'>
                                <h3>" . htmlspecialchars($row['name']) . "</h3>
                                <p><strong>Category:</strong> " . htmlspecialchars($row['category']) . "</p>
                                <p><strong>Model Year:</strong> " . htmlspecialchars($row['model_year']) . "</p>
                                <p><strong>Condition:</strong> " . htmlspecialchars($row['condition']) . "</p>
                                <p><strong>Price:</strong> $" . number_format($row['price'], 2) . "</p>
                                <p><strong>Kilometer:</strong> " . number_format($row['kilometer']) . " km</p> <!-- Display kilometer here -->
                            </div>
                        </div>";
                }
            } else {
                echo "<p>No second-hand cars available.</p>";
            }
            ?>
        </div>
    </div>

    <script>
        // Add any additional functionality here (like loading more cars via AJAX, etc.)
    </script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
