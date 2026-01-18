<?php
// Include database connection
include('connection.php');

// Get the car ID from the URL (passed from index.php)
$car_id = isset($_GET['car_id']) ? $_GET['car_id'] : null;

// Ensure car_id is present and valid
if ($car_id === null) {
    echo "<p>Car ID is missing. Please provide a valid car ID.</p>";
    exit;
}

// Fetch the car details from the database
$car_sql = "SELECT * FROM second_hand_cars WHERE id = ?";
$stmt = $conn->prepare($car_sql);
$stmt->bind_param('i', $car_id);
$stmt->execute();
$car_result = $stmt->get_result();

if ($car_result->num_rows > 0) {
    $car = $car_result->fetch_assoc();
    $car_name = $car['name'];
    $car_price = $car['price'];
    $car_model_year = $car['model_year'];
    $car_kilometer = $car['kilometer'];
    $car_image = $car['image'] ? 'assets/images/' . $car['image'] : 'assets/images/default_car_image.jpg';
} else {
    echo "<p>Car not found. Please check the car ID.</p>";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form - <?php echo isset($car_name) ? $car_name : 'Unknown Car'; ?></title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: #f4f7fc;
            color: #444;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 900px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 30px;
        }

        h1 {
            font-size: 28px;
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .car-details {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .car-details img {
            width: 100%;
            max-width: 350px;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .car-details p {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
        }

        .car-details strong {
            color: #007bff;
        }

        .form-section {
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="month"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 14px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            background-color: #f8f8f8;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="month"]:focus,
        input[type="number"]:focus,
        select:focus,
        textarea:focus {
            border-color: #007bff;
            background-color: #ffffff;
        }

        button {
            width: 100%;
            padding: 14px;
            font-size: 18px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        button:active {
            transform: scale(0.98);
        }

        .terms {
            font-size: 14px;
            text-align: center;
            margin-top: 20px;
        }

        .terms a {
            color: #007bff;
            text-decoration: none;
        }

        .terms a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 100%;
                padding: 20px;
            }

            .car-details img {
                max-width: 250px;
            }

            button {
                font-size: 16px;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Payment for <?php echo isset($car_name) ? $car_name : 'Unknown Car'; ?></h1>
        <div class="car-details">
            <img src="<?php echo isset($car_image) ? $car_image : 'assets/images/default_car_image.jpg'; ?>" alt="Car Image">
            <p><strong>Model Year:</strong> <?php echo isset($car_model_year) ? $car_model_year : 'N/A'; ?></p>
            <p><strong>Kilometer:</strong> <?php echo isset($car_kilometer) ? $car_kilometer : 'N/A'; ?> km</p>
            <p><strong>Price:</strong> $<?php echo isset($car_price) ? number_format($car_price, 2) : '0.00'; ?></p>
        </div>

        <form action="secondhand_process_payment.php" method="POST">
            <input type="hidden" name="car_id" value="<?php echo isset($car_id) ? $car_id : ''; ?>">

            <!-- Buyer Information Section -->
            <div class="form-section">
                <label for="buyer_name">Your Name:</label>
                <input type="text" name="buyer_name" required>

                <label for="buyer_email">Your Email:</label>
                <input type="email" name="buyer_email" required>

                <label for="buyer_phone">Your Phone:</label>
                <input type="text" name="buyer_phone" required>

                <label for="buyer_address">Your Address:</label>
                <textarea name="buyer_address" required></textarea>
            </div>

            <!-- Payment Information Section -->
            <div class="form-section">
                <label for="payment_method">Payment Method:</label>
                <select name="payment_method" id="payment_method" required>
                    <option value="credit_card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                    <option value="bank_transfer">Bank Transfer</option>
                </select>

                <!-- Payment method details go here -->

            </div>

            <!-- Billing Address Section -->
            <div class="form-section">
                <label for="billing_address">Billing Address:</label>
                <textarea name="billing_address" required></textarea>
            </div>

            <!-- Terms and Conditions -->
            <div class="form-section">
                <label for="accept_terms">
                    <input type="checkbox" name="accept_terms" required> I accept the <a href="#">terms and conditions</a>.
                </label>
            </div>

            <button type="submit">Submit Payment</button>
        </form>

        <div class="terms">
            <p>By completing this form, you agree to our <a href="#">Terms and Conditions</a></p>
        </div>
    </div>
</body>
</html>
