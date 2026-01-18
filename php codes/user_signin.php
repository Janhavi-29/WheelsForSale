<?php
session_start(); // Start the session

if (isset($_POST['signin'])) {
    include('connection.php'); // Include the database connection file

    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL query to fetch user data from the users table
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['name'] = $row['name']; // Store user's name in session
            header("Location: index.php"); // Redirect to home page
            exit; // Ensure the script stops after redirection
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No user found with this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Sign In</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <div class="nav-boxes">
        <div class="nav-box" onclick="location.href='user_signup.php'">Sign Up</div>
        <div class="nav-box" onclick="location.href='admin_signin.php'">Admin Sign In</div>
        <div class="nav-box" onclick="location.href='index.php'">Home</div>
    </div>

    <div class="container">
        <h1>User Sign In</h1>
        <form method="POST" action="user_signin.php">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit" name="signin">Sign In</button>
        </form>
    </div>
</body>
</html>
