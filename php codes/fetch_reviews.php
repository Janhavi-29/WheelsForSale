<?php
// Include your database connection
include('connection.php');

header('Content-Type: application/json');

// Query to fetch reviews data from the reviews table
$sql = "SELECT id, name, email, review, rating, submitted_at FROM reviews";
$result = mysqli_query($conn, $sql);

$reviews = array();
while ($row = mysqli_fetch_assoc($result)) {
    $reviews[] = $row;
}

// Return the data as a JSON response
echo json_encode($reviews);
?>
