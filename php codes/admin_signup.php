<?php
session_start(); // Start the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign Up</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <!-- Navigation buttons above the form -->
    <div class="nav-boxes">
        <div class="nav-box" onclick="location.href='admin_signin.php'">Admin Sign In</div>
        <div class="nav-box" onclick="location.href='user_signup.php'">User Sign Up</div>
        <div class="nav-box" onclick="location.href='index.php'">Home</div>
    </div>

    <div class="container">
        <h1>Admin Sign Up</h1>
        <form method="POST" action="admin_signup.php">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit" name="signup">Sign Up</button>
        </form>
    </div>

    <?php
    if (isset($_POST['signup'])) {
        include('connection.php'); // Include the database connection file

        // Get admin input
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Insert admin data into the admin table
        $sql = "INSERT INTO admin (name, email, password) VALUES ('$name', '$email', '$password')";

        if (mysqli_query($conn, $sql)) {
            // Store admin data in session and set the admin flag
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['is_admin'] = true;

            // Redirect to the admin dashboard
            header("Location: admin_dashboard.php");
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
    ?>
</body>
</html>
