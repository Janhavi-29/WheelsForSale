<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection settings
$host = "localhost"; // Server
$username = "root";  // Username
$password = "";      // Password (use "" if you're using XAMPP default)
$database = "cars";  // Database name

// Create the connection
$conn = new mysqli($host, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Terminate if connection fails
}
?>
