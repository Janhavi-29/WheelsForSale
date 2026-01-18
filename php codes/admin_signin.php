<?php
session_start(); // Start the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign In</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <!-- Navigation buttons above the form -->
    <div class="nav-boxes">
        <div class="nav-box" onclick="location.href='admin_signup.php'">Admin Sign Up</div>
        <div class="nav-box" onclick="location.href='user_signin.php'">User Sign In</div>
        <div class="nav-box" onclick="location.href='index.php'">Home</div>
    </div>

    <div class="container">
        <h1>Admin Sign In</h1>
        <form method="POST" action="admin_signin.php">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit" name="signin">Sign In</button>
        </form>
    </div>

    <?php
    if (isset($_POST['signin'])) {
        include('connection.php'); // Include the database connection file

        $email = $_POST['email'];
        $password = $_POST['password'];

        // SQL query to fetch admin data from the admin table
        $sql = "SELECT * FROM admin WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                // Store admin data in session and set the admin flag
                $_SESSION['name'] = $row['name'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['is_admin'] = true;

                // Redirect to the admin dashboard
                header("Location: admin_dashboard.php");
                exit;
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "No admin found with this email.";
        }
    }
    ?>
</body>
</html>
