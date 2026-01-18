<?php
session_start(); // Start the session

if (isset($_POST['signup'])) {
    include('connection.php'); // Include the database connection file

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password before storing

    // Insert user data into the users table
    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

    if (mysqli_query($conn, $sql)) {
        // If successful, redirect to the homepage
        $_SESSION['name'] = $name; // Store user's name in session
        header("Location: index.php"); // Redirect to home page
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);  // Show any MySQL errors
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Sign Up</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <div class="nav-boxes">
        <div class="nav-box" onclick="location.href='user_signin.php'">Sign In</div>
        <div class="nav-box" onclick="location.href='admin_signup.php'">Admin Sign Up</div>
        <div class="nav-box" onclick="location.href='index.php'">Home</div>
    </div>

    <div class="container">
        <h1>User Sign Up</h1>
        <form method="POST" action="user_signup.php">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit" name="signup">Sign Up</button>
        </form>
    </div>
</body>
</html>
