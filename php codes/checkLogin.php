<?php
session_start();

// Check if the user is logged in by checking the session variable
$response = array('loggedIn' => isset($_SESSION['name']));  // Check if 'name' session variable is set
echo json_encode($response); // Return the result as JSON
?>
