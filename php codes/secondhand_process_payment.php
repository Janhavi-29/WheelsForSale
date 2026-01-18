<?php
// Include database connection
include('connection.php');

// Check if the form fields are set
if (isset($_POST['buyer_name'], $_POST['buyer_email'], $_POST['buyer_phone'], $_POST['buyer_address'], $_POST['payment_method'], $_POST['billing_address'], $_POST['car_id'])) {

    // Get the posted data from the form
    $buyer_name = $_POST['buyer_name'];
    $buyer_email = $_POST['buyer_email'];
    $buyer_phone = $_POST['buyer_phone'];
    $buyer_address = $_POST['buyer_address'];
    $payment_method = $_POST['payment_method'];
    $billing_address = $_POST['billing_address'];
    $car_id = $_POST['car_id'];

    // Prepare payment details based on the selected method
    $credit_card_number = isset($_POST['credit_card_number']) ? $_POST['credit_card_number'] : NULL;
    $credit_card_expiry = isset($_POST['credit_card_expiry']) ? $_POST['credit_card_expiry'] : NULL;
    $credit_card_cvc = isset($_POST['credit_card_cvc']) ? $_POST['credit_card_cvc'] : NULL;
    $paypal_email = isset($_POST['paypal_email']) ? $_POST['paypal_email'] : NULL;
    $bank_account = isset($_POST['bank_account']) ? $_POST['bank_account'] : NULL;
    $bank_name = isset($_POST['bank_name']) ? $_POST['bank_name'] : NULL;

    // Insert payment details into the database
    $insert_sql = "INSERT INTO second_car_purchase (car_id, buyer_name, buyer_email, buyer_phone, buyer_address, payment_method, credit_card_number, credit_card_expiry, credit_card_cvc, paypal_email, bank_account, bank_name, billing_address) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($insert_sql);

    // Check if the prepared statement was created successfully
    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    // Bind the parameters to the prepared statement
    $stmt->bind_param('issssssssssss', $car_id, $buyer_name, $buyer_email, $buyer_phone, $buyer_address, $payment_method, $credit_card_number, $credit_card_expiry, $credit_card_cvc, $paypal_email, $bank_account, $bank_name, $billing_address);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Redirect to the thank you page after successful payment
        header("Location: thank_you.php?car_id=" . $car_id);
        exit();
    } else {
        // If the insert failed, show the error message
        echo "Error processing payment: " . $stmt->error;
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Handle the error if required form data is missing
    echo "Error: Some form fields are missing.";
}
?>
