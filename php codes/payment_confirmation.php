<?php
include('connection.php');
session_start();

// Get the payment ID from the URL
$payment_id = isset($_GET['payment_id']) ? $_GET['payment_id'] : null;

if ($payment_id) {
    // Fetch payment details based on the payment ID
    $sql = "SELECT * FROM car_payment WHERE id = '$payment_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $payment = mysqli_fetch_assoc($result);
        $car_id = $payment['car_id'];

        // Fetch car details based on the car ID
        $car_sql = "SELECT * FROM cars WHERE id = '$car_id'";
        $car_result = mysqli_query($conn, $car_sql);
        if (mysqli_num_rows($car_result) > 0) {
            $car = mysqli_fetch_assoc($car_result);
        }
    } else {
        die('Payment not found.');
    }
} else {
    die('Invalid payment ID.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 2.5rem;
        }

        .thank-you-message {
            text-align: center;
            margin: 20px 0;
        }

        .thank-you-message p {
            font-size: 1.25rem;
            line-height: 1.8;
            color: #555;
        }

        .car-details {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 30px;
        }

        .car-details img {
            width: 100%;
            max-width: 1100px;
            height: 500px;
            border-radius: 10px;
            object-fit: cover;
        }

        .car-info {
            margin-top: 20px;
            font-size: 1.2rem;
            color: #333;
        }

        .car-info p {
            margin: 10px 0;
        }

        .back-button {
            display: block;
            width: 200px;
            margin: 30px auto 0;
            padding: 15px;
            text-align: center;
            background-color: #e50914;
            color: white;
            font-size: 1.2rem;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #f40612;
        }

        .payment-details {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .payment-details h3 {
            margin-bottom: 20px;
            color: #333;
        }

        .payment-details p {
            margin: 5px 0;
            color: #555;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thank You for Your Purchase!</h1>
        
        <div class="thank-you-message">
            <p>
                Congratulations on purchasing your new car! We’re excited to have you as part of our family of customers.
                Your purchase is now confirmed and we’ll make sure to deliver your car in the best condition.
                You’ll receive a follow-up email with all the details about your order and delivery.
            </p>
            <p>
                If you have any questions or need further assistance, feel free to contact us.
                Thank you for trusting us with your purchase!
            </p>
        </div>

        <div class="car-details">
            <img src="assets/images/<?php echo htmlspecialchars($car['image']); ?>" alt="Car Image">
            <div class="car-info">
                <p><strong>Car Name:</strong> <?php echo htmlspecialchars($car['name']); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($car['category']); ?></p>
                <p><strong>Price:</strong> $<?php echo htmlspecialchars($car['price']); ?></p>
                <p><strong>Model Year:</strong> <?php echo htmlspecialchars($car['model_year']); ?></p> <!-- Display model year -->
            </div>
        </div>

        <div class="payment-details">
            <h3>Payment Confirmation Details</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($payment['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($payment['email']); ?></p>
            <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($payment['payment_status']); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($payment['payment_method']); ?></p>
        </div>

        <a href="index.php" class="back-button">Back to Home</a>
    </div>
</body>
</html>
