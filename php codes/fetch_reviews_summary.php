<?php
include('connection.php');

// Query to get counts based on the updated rating thresholds
$query = "SELECT 
            COUNT(*) AS total_reviews, 
            SUM(CASE WHEN rating = 5 OR rating = 4 THEN 1 ELSE 0 END) AS happy,
            SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) AS moderate,
            SUM(CASE WHEN rating = 1 OR rating = 2 THEN 1 ELSE 0 END) AS unhappy
          FROM reviews";

// Execute the query
$result = mysqli_query($conn, $query);

// Check if the query was successful and data is returned
if ($result) {
    $row = mysqli_fetch_assoc($result);

    // Return the count of happy, moderate, and unhappy reviews
    echo json_encode([
        'happy' => (int)$row['happy'],
        'moderate' => (int)$row['moderate'],
        'unhappy' => (int)$row['unhappy']
    ]);
} else {
    // Return counts as 0 if no data exists or query fails
    echo json_encode([
        'happy' => 0,
        'moderate' => 0,
        'unhappy' => 0
    ]);
}

// Close the database connection
mysqli_close($conn);
?>
