<?php
// fetch_users.php
include 'connection.php'; // Include your database connection

// Prepare and execute the query to fetch users
$query = "SELECT id, name, email, password, created_at FROM users";
$result = $conn->query($query);

// Initialize an array to store user data
$users = [];

if ($result->num_rows > 0) {
    // Loop through the result set and fetch each user
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
} else {
    // If no users found, return an empty array
    $users = [];
}

// Send the data as JSON
header('Content-Type: application/json');
echo json_encode($users);

// Close the database connection
$conn->close();
?>
