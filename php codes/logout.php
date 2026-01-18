<?php
session_start(); // Start the session

// Check if the user is logged in as an admin
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    // If logged in as admin, redirect to admin sign-in page
    header("Location: admin_signin.php");
} else {
    // If logged in as a user or no session, redirect to homepage
    header("Location: index.php");
}

// Destroy the session to log the user out
session_unset();
session_destroy();
exit();
?>


