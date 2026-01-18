<?php
// remove_user.php
include 'connection.php'; // Include the database connection file

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Check if userId is provided in the request
if (isset($data['userId']) && is_numeric($data['userId'])) {
    $userId = intval($data['userId']); // Sanitize userId input

    // Prepare the SQL query to delete the user
    $sql = "DELETE FROM users WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $userId);

        // Execute the query
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'User not found.']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete user.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Query preparation failed.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid or missing User ID.']);
}

$conn->close();
?>
