<?php
session_start();
include('connection.php');

// Check if the user is logged in and is an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_signin.php");
    exit;
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $category = $_POST['category'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $model_year = $_POST['model_year']; // New field for model year
    $image = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];
    $imageError = $_FILES['image']['error'];

    // Define allowed file types
    $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];

    // Check if there was an error in file upload
    if ($imageError != 0) {
        echo "Error uploading the file. Please try again.";
        exit();
    }

    // Check if the uploaded file is an allowed type
    if (!in_array($_FILES['image']['type'], $allowedFileTypes)) {
        echo "Invalid file type. Only JPG, PNG, and GIF files are allowed.";
        exit();
    }

    // Ensure the directory exists
    if (!is_dir('assets/images')) {
        mkdir('assets/images', 0755, true); // Create the directory if it does not exist
    }

    // Sanitize image name
    $image = basename($image);  // Remove any directory path
    $imagePath = 'assets/images/' . $image;

    // Move uploaded image to the specified directory
    if (!move_uploaded_file($imageTmp, $imagePath)) {
        echo "Failed to upload image. Please try again.";
        exit();
    }

    // Insert data into the database
    $sql = "INSERT INTO cars (name, category, price, model_year, image) 
            VALUES ('$name', '$category', '$price', '$model_year', '$image')";

    if (mysqli_query($conn, $sql)) {
        header("Location: admin_dashboard.php?message=Car added successfully");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Car</title>
</head>
<body>
    <h1>Add New Car</h1>
    <form action="add_car.php" method="POST" enctype="multipart/form-data">
        <label for="category">Category:</label>
        <select name="category" id="category" required>
            <option value="SUV">SUV</option>
            <option value="Sedan">Sedan</option>
            <option value="Truck">Truck</option>
            <option value="Coupe">Coupe</option>
            <option value="Convertible">Convertible</option>
        </select><br><br>

        <label for="name">Car Name:</label>
        <input type="text" name="name" id="name" required><br><br>

        <label for="price">Price ($):</label>
        <input type="number" name="price" id="price" required><br><br>

        <label for="model_year">Model Year:</label>
        <input type="number" name="model_year" id="model_year" required><br><br>

        <label for="image">Car Image:</label>
        <input type="file" name="image" id="image" accept="image/*" required><br><br>

        <button type="submit">Add Car</button>
    </form>
</body>
</html>
