<?php
// Include database connection
include('connection.php');

// Get car_id from URL (the one we passed from the form submission)
$car_id = isset($_GET['car_id']) ? $_GET['car_id'] : null;
$buyer_name = isset($_GET['buyer_name']) ? $_GET['buyer_name'] : '';

// Ensure car_id is set
if ($car_id === null) {
    echo "<p>No car ID provided. Something went wrong.</p>";
    exit;
}

// Fetch the car details from second_hand_cars table
$car_sql = "SELECT * FROM second_hand_cars WHERE id = ?";
$car_stmt = $conn->prepare($car_sql);
$car_stmt->bind_param('i', $car_id);
$car_stmt->execute();
$car_result = $car_stmt->get_result();

if ($car_result->num_rows > 0) {
    $car = $car_result->fetch_assoc();
    $car_name = $car['name'];
    $car_price = $car['price'];
    $car_image = $car['image'] ? 'assets/images/' . $car['image'] : 'assets/images/default_car_image.jpg';
} else {
    echo "<p>Car not found.</p>";
    exit;
}

// Fetch the billing details from second_car_purchase table
$purchase_sql = "SELECT * FROM second_car_purchase WHERE car_id = ?";
$purchase_stmt = $conn->prepare($purchase_sql);
$purchase_stmt->bind_param('i', $car_id);
$purchase_stmt->execute();
$purchase_result = $purchase_stmt->get_result();

if ($purchase_result->num_rows > 0) {
    $purchase = $purchase_result->fetch_assoc();
    $buyer_email = $purchase['buyer_email'];
    $buyer_phone = $purchase['buyer_phone'];
    $buyer_address = $purchase['buyer_address'];
    $payment_method = $purchase['payment_method'];
    $billing_address = $purchase['billing_address'];
} else {
    echo "<p>Purchase details not found.</p>";
    exit;
}

// Close the database connections
$car_stmt->close();
$purchase_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Your Purchase</title>
    <style>
        /* General Reset and Font */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fc;
            color: #444;
            line-height: 1.6;
            padding: 40px;
        }

        /* Container Styling */
        .thank-you-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
        }

        h1 {
            font-size: 36px;
            color: #1e6d34;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        p {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
        }

        .congratulations {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 30px;
        }

        .car-image {
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .car-details, .billing-details {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            text-align: left;
            margin-left: auto;
            margin-right: auto;
            width: 80%;
        }

        .car-details p, .billing-details p {
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }

        .car-details strong, .billing-details strong {
            color: #007bff;
        }

        .back-button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #218838;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 20px;
            }
            .thank-you-container {
                padding: 20px;
            }
            .car-image {
                max-width: 90%;
            }
            .back-button {
                width: 100%;
                text-align: center;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <div class="thank-you-container">
        <h1>Thank You for Your Purchase, <?php echo htmlspecialchars($buyer_name); ?>!</h1>

        <!-- Congratulations Message -->
        <div class="congratulations">
            Congratulations on your new car! 🚗 We're excited for you!
        </div>

        <p>Your order has been successfully processed. Below are the details of the car you purchased:</p>

        <!-- Car Details Section -->
        <div class="car-details">
            <img src="<?php echo $car_image; ?>" alt="Car Image" class="car-image">
            <p><strong>Car Name:</strong> <?php echo htmlspecialchars($car_name); ?></p>
            <p><strong>Price:</strong> $<?php echo number_format($car_price, 2); ?></p>
        </div>

        <!-- Billing Information Section -->
        <div class="billing-details">
            <h3>Billing Information</h3>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($buyer_email); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($buyer_phone); ?></p>
            <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($buyer_address)); ?></p>
            <p><strong>Payment Method:</strong> <?php echo ucfirst(htmlspecialchars($payment_method)); ?></p>
            <p><strong>Billing Address:</strong> <?php echo nl2br(htmlspecialchars($billing_address)); ?></p>
        </div>

        <!-- Back to Homepage Button -->
        <a href="index.php" class="back-button">Back to Homepage</a>
    </div>

</body>
</html>
