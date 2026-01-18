<?php
include('connection.php');
session_start(); // Make sure the session is started

// Get the car ID from the URL (if passed)
$car_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($car_id) {
    // Fetch car details based on the ID
    $sql = "SELECT * FROM cars WHERE id = '$car_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $car = mysqli_fetch_assoc($result);
    } else {
        die('Car not found.');
    }
} else {
    die('Invalid car ID.');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the payment form submission
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $billing_address = mysqli_real_escape_string($conn, $_POST['billing_address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $card_number = mysqli_real_escape_string($conn, $_POST['card_number']);
    $expiration_date = mysqli_real_escape_string($conn, $_POST['expiration_date']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $payment_status = mysqli_real_escape_string($conn, $_POST['payment_status']); // Completed or Pending

    // Insert the payment data into the car_payment table
    $payment_sql = "INSERT INTO car_payment (car_id, name, email, address, billing_address, phone, card_number, expiration_date, payment_method, payment_status) 
                    VALUES ('$car_id', '$name', '$email', '$address', '$billing_address', '$phone', '$card_number', '$expiration_date', '$payment_method', '$payment_status')";

    if (mysqli_query($conn, $payment_sql)) {
        // Get the payment ID after insertion
        $payment_id = mysqli_insert_id($conn);
        // Redirect to the confirmation page with the payment ID
        header("Location: payment_confirmation.php?payment_id=" . $payment_id);
        exit(); // Ensure no further code is executed after the redirect
    } else {
        echo '<p>Error submitting payment data: ' . mysqli_error($conn) . '</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment for <?php echo htmlspecialchars($car['name']); ?></title>
    <style>
body {
    font-family: 'Roboto', sans-serif;
    background-color: #f8f8f8;
    margin: 0;
    padding: 0;
    color: #333;
}

.container {
    width: 70%;
    margin: 50px auto;
    background-color: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #333;
    font-size: 2.5rem;
    margin-bottom: 30px;
    font-weight: 600;
}

.car-details {
    display: flex;
    justify-content: space-between;
    margin-bottom: 30px;
    border-bottom: 2px solid #ddd;
    padding-bottom: 20px;
}

.car-details h3 {
    color: #444;
    font-size: 1.3rem;
    font-weight: 500;
}

.car-details p {
    font-size: 1.1rem;
    color: #555;
    margin: 5px 0;
}

.car-details img {
    width: 440px;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
    font-size: 1.1rem;
}

.form-group input, .form-group textarea, .form-group select {
    width: 100%;
    padding: 12px;
    font-size: 1rem;
    border: 2px solid #ddd;
    border-radius: 8px;
    box-sizing: border-box;
    transition: border-color 0.3s;
}

.form-group input:focus, .form-group textarea:focus, .form-group select:focus {
    border-color: #e50914;
    outline: none;
}

.form-group textarea {
    height: 120px;
}

select {
    background-color: #fafafa;
}

button[type="submit"] {
    width: 100%;
    padding: 14px;
    background-color: #e50914;
    color: white;
    font-size: 1.2rem;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    box-sizing: border-box;
}

button[type="submit"]:hover {
    background-color: #f40612;
}

button[type="submit"]:active {
    background-color: #b4060e;
}

.highlight {
    color: #e50914;
    font-weight: 600;
}

.car-details p strong {
    color: #333;
}

.form-group input::placeholder, .form-group textarea::placeholder {
    color: #aaa;
}

footer {
    text-align: center;
    margin-top: 30px;
    color: #777;
}

footer a {
    color: #e50914;
    text-decoration: none;
}

footer a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .container {
        width: 90%;
    }

    .car-details {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .car-details img {
        width: 80%;
        margin-top: 20px;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Payment for <?php echo htmlspecialchars($car['name']); ?></h1>

        <div class="car-details">
            <div>
                <h3>Car Details</h3>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($car['category']); ?></p>
                <p><strong>Model Year:</strong> <?php echo htmlspecialchars($car['model_year']); ?></p>
                <p><strong>Price:</strong> $<?php echo htmlspecialchars($car['price']); ?></p>
            </div>
            <img src="assets/images/<?php echo htmlspecialchars($car['image']); ?>" alt="<?php echo htmlspecialchars($car['name']); ?>">
        </div>

        <h3>Enter Payment Details</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="address">Shipping Address</label>
                <textarea id="address" name="address" required></textarea>
            </div>

            <div class="form-group">
                <label for="billing_address">Billing Address (if different)</label>
                <textarea id="billing_address" name="billing_address"></textarea>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="card_number">Card Number</label>
                <input type="text" id="card_number" name="card_number" required>
            </div>

            <div class="form-group">
                <label for="expiration_date">Expiration Date (MM/YY)</label>
                <input type="text" id="expiration_date" name="expiration_date" required>
            </div>

            <div class="form-group">
                <label for="payment_method">Payment Method</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="credit_card">Credit Card</option>
                    <option value="debit_card">Debit Card</option>
                    <option value="paypal">PayPal</option>
                    <option value="bank_transfer">Bank Transfer</option>
                </select>
            </div>

            <div class="form-group">
                <label for="payment_status">Payment Status</label>
                <select id="payment_status" name="payment_status" required>
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>

            <button type="submit" class="btn">Submit Payment</button>
        </form>
    </div>
</body>
</html>
