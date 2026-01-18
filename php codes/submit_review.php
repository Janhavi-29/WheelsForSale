<?php
// Include the database connection file
include('connection.php');

// Check if the connection is established
if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . mysqli_connect_error()]);
    exit();
}

// Process the form data after validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $review = $_POST['review'];
    $rating = $_POST['rating'];

    // Prepare the SQL query
    $query = "INSERT INTO reviews (name, email, review, rating) VALUES ('$name', '$email', '$review', '$rating')";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        echo json_encode(["status" => "success", "message" => "Your review has been successfully submitted!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "There was an error submitting your review. Please try again later."]);
    }
}
?>
