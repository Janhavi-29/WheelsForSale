<?php
require_once 'connection.php'; // Include your database connection

// Fetch all contact submissions
$sql = "SELECT * FROM contact_us ORDER BY submitted_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $contacts = [];
    while ($row = $result->fetch_assoc()) {
        $contacts[] = $row;
    }
    echo json_encode($contacts); // Return data as JSON
} else {
    echo json_encode([]); // Return empty array if no data
}

$conn->close();
?>
