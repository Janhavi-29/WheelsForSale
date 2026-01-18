<?php
include('connection.php');

// Check if the connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Process the form data after validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $review = $_POST['review'];
    $rating = $_POST['rating'];

    // Prepare the SQL query to insert review
    $query = "INSERT INTO reviews (name, email, review, rating) VALUES ('$name', '$email', '$review', '$rating')";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Your review has been successfully submitted!'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'There was an error submitting your review. Please try again later.'
        ]);
    }
}

// Fetch review counts categorized by ratings
$query = "SELECT COUNT(*) AS happy_count FROM reviews WHERE rating >= 4";
$happy_result = mysqli_query($conn, $query);
$happy_count = mysqli_fetch_assoc($happy_result)['happy_count'];

$query = "SELECT COUNT(*) AS moderate_count FROM reviews WHERE rating = 3";
$moderate_result = mysqli_query($conn, $query);
$moderate_count = mysqli_fetch_assoc($moderate_result)['moderate_count'];

$query = "SELECT COUNT(*) AS unhappy_count FROM reviews WHERE rating <= 2";
$unhappy_result = mysqli_query($conn, $query);
$unhappy_count = mysqli_fetch_assoc($unhappy_result)['unhappy_count'];

// Return the counts as JSON for the admin panel to update the review summary
echo json_encode([
    'happy_count' => $happy_count,
    'moderate_count' => $moderate_count,
    'unhappy_count' => $unhappy_count
]);

?>
