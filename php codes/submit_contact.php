<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the database connection
include('connection.php');  // Ensure this is the correct path

// Check if form data is received
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from the form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Prepare the SQL query
    $stmt = $conn->prepare("INSERT INTO contact_us (name, email, phone, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $message); // "ssss" means 4 strings

    // Execute the query
    if ($stmt->execute()) {
        echo "success";  // Return success if data inserted
    } else {
        echo "error: " . $stmt->error;  // Return error message if insertion fails
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
