<?php
// Assuming $conn is your database connection
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Insert the data into the database
    $query = "INSERT INTO contact_us (name, email, phone, message) VALUES ('$name', '$email', '$phone', '$message')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script type='text/javascript'>
                alert('Your message has been successfully sent. We will get back to you soon!');
                window.location.href = 'your-landing-page.php'; // Redirect to the contact page or any other page
              </script>";
    } else {
        echo "<script type='text/javascript'>
                alert('There was an error sending your message. Please try again later.');
                window.location.href = 'your-landing-page.php';
              </script>";
    }
}
?>
