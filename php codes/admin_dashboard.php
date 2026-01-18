<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_signin.php");
    exit;
}

include('connection.php');

// Handle car deletion for the new cars section
if (isset($_GET['delete_new_car_id'])) {
    $delete_new_car_id = $_GET['delete_new_car_id'];

    // First delete any related records in car_payment table
    $delete_payment_sql = "DELETE FROM car_payment WHERE car_id = '$delete_new_car_id'";
    mysqli_query($conn, $delete_payment_sql); // Deleting related payments

    // Now delete the car from the cars table
    $delete_new_car_sql = "DELETE FROM cars WHERE id = '$delete_new_car_id'";
    if (mysqli_query($conn, $delete_new_car_sql)) {
        echo "<script>alert('Car deleted successfully'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error deleting car'); window.location.href='admin_dashboard.php';</script>";
    }
}

// Handle car deletion

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Delete the car from the second_hand_cars table
    $delete_sql = "DELETE FROM second_hand_cars WHERE id = '$delete_id'";
    if (mysqli_query($conn, $delete_sql)) {
        echo "<script>alert('Second-hand car deleted successfully'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error deleting second-hand car'); window.location.href='admin_dashboard.php';</script>";
    }
}


// Handle category filter
$category_filter = isset($_POST['category_filter']) ? $_POST['category_filter'] : '';

// Get the list of categories for the filter dropdown
$categories = ['SUV', 'Sedan', 'Hatchback', 'Pickuptruck', 'Convertible', 'Luxury'];

// Handle second-hand car form submission
if (isset($_POST['submit'])) {
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $model_year = mysqli_real_escape_string($conn, $_POST['model_year']);
    $condition = mysqli_real_escape_string($conn, $_POST['condition']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $kilometer = mysqli_real_escape_string($conn, $_POST['kilometer']);
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = time() . '_' . $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = 'assets/images/' . $image_name;

        if (move_uploaded_file($image_tmp, $image_path)) {
            // Insert the second-hand car into the database
            $insert_sql = "INSERT INTO second_hand_cars (category, name, model_year, `condition`, price, image, kilometer)
                           VALUES ('$category', '$name', '$model_year', '$condition', '$price', '$image_name', '$kilometer')";
            if (mysqli_query($conn, $insert_sql)) {
                echo "<script>alert('Second-hand car added successfully'); window.location.href='admin_dashboard.php';</script>";
            } else {
                echo "<script>alert('Error adding second-hand car');</script>";
            }
        } else {
            echo "<script>alert('Error uploading image');</script>";
        }
    } else {
        echo "<script>alert('Please upload a valid image');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin_panel.css">
    <link rel="stylesheet" href="css/cards.css">

   
    <style>
        /* General styling for the dashboard section */
        #dashboard {
            padding: 20px;
        }

        #dashboard h1 {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        #dashboard p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        /* Styling for the container of boxes */
        .dash_boxes {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }

        /* Styling for individual boxes */
        .box {
            flex: 1 1 calc(20% - 20px); /* 4 boxes in a row by default (desktop) */
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            min-height: 150px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        .box h3 {
            margin-bottom: 15px;
            font-size: 18px;
        }

        .box p {
            font-size: 24px;
            font-weight: bold;
        }

        /* Color the values in the boxes with different colors */
        .box:nth-child(1) p {
            color: #4CAF50; /* Green */
        }

        .box:nth-child(2) p {
            color: #FF5722; /* Orange */
        }

        .box:nth-child(3) p {
            color: #2196F3; /* Blue */
        }

        .box:nth-child(4) p {
            color: #FF9800; /* Amber */
        }

        /* Responsive behavior for the boxes */

        /* When the screen width is 1200px or less (3 boxes in a row) */
        @media (max-width: 1200px) {
            .box {
                flex: 1 1 calc(33.33% - 20px); /* 3 boxes in a row */
            }
        }

        /* When the screen width is 768px or less (2 boxes in a row) */
        @media (max-width: 768px) {
            .box {
                flex: 1 1 calc(50% - 20px); /* 2 boxes in a row */
            }
        }

        /* When the screen width is 480px or less (1 box in a row) */
        @media (max-width: 480px) {
            .box {
                flex: 1 1 100%; /* 1 box in a row */
            }
        }
    </style>


</head>
<body>
    <div class="sidebar">
        <h2>Admin</h2>
        <ul>
            <li><a href="#" onclick="showSection('dashboard')">Dashboard</a></li>
            <li><a href="#" onclick="showSection('cars')">Cars</a></li>
            <li><a href="#" onclick="showSection('users')">Users</a></li>
            <li><a href="#" onclick="showSection('reviews')">Reviews</a></li>
            <li><a href="#" onclick="showSection('contact')">Contact Us</a></li>
            <li><a href="#" onclick="showSection('addCars')">Add New Cars</a></li>
            <li><a href="#" onclick="showSection('secondhandcars')">Second Hand Cars</a></li>
            <li><a href="#" onclick="showSection('settings')">Settings</a></li>
            <li><a href="logout.php" class="logout-btn">Logout</a></li>
        </ul>
    </div>

    <div class="content">
        <div class="navbar">
            <h1>Admin Dashboard</h1>
            <span class="admin-name">Welcome, Admin</span>
        </div>

<!-- Dashboard Section -->
<div id="dashboard" class="section active">
    <h1>Dashboard</h1>
    <p>Overview of the admin activities.</p>

    <!-- Dashboard Overview Section -->
    <div class="dash_boxes">

        <!-- Box 1: Total New Cars in Showcase -->
        <div class="box dash_box">
            <h3>Total New Cars in Showcase</h3>
            <p>
                <?php
                // Fetch total number of new cars from the 'cars' table
                $new_car_count_sql = "SELECT COUNT(*) AS total_new_cars FROM cars";
                $new_car_count_result = mysqli_query($conn, $new_car_count_sql);
                $new_car_count = mysqli_fetch_assoc($new_car_count_result);
                echo $new_car_count['total_new_cars'];
                ?>
            </p>
        </div>

        <!-- Box 2: Total Second Hand Cars in Showcase -->
        <div class="box dash_box">
            <h3>Total Second Hand Cars in Showcase</h3>
            <p>
                <?php
                // Fetch total number of second-hand cars from the 'second_hand_cars' table
                $second_hand_car_count_sql = "SELECT COUNT(*) AS total_second_hand_cars FROM second_hand_cars";
                $second_hand_car_count_result = mysqli_query($conn, $second_hand_car_count_sql);
                $second_hand_car_count = mysqli_fetch_assoc($second_hand_car_count_result);
                echo $second_hand_car_count['total_second_hand_cars'];
                ?>
            </p>
        </div>

    </div>

    <div class="dash_boxes">

        <!-- Box 3: Total Purchases -->
        <div class="box dash_box">
            <h3>Total Purchases</h3>
            <p>
                <?php
                // Fetch total new car purchases from 'car_payment' table and get the price from 'cars' table
                $new_car_purchases_sql = "SELECT COUNT(cp.id) AS total_new_purchases 
                                          FROM car_payment cp 
                                          JOIN cars c ON cp.car_id = c.id 
                                          WHERE cp.payment_status = 'completed'";
                $new_car_purchases_result = mysqli_query($conn, $new_car_purchases_sql);
                $new_car_purchases = mysqli_fetch_assoc($new_car_purchases_result);

                // Fetch total second hand car purchases from 'second_car_purchase' table and get the price from 'second_hand_cars' table
                $second_hand_car_purchases_sql = "SELECT COUNT(sc.id) AS total_second_hand_purchases 
                                                  FROM second_car_purchase sc 
                                                  JOIN second_hand_cars shc ON sc.car_id = shc.id";
                $second_hand_car_purchases_result = mysqli_query($conn, $second_hand_car_purchases_sql);
                $second_hand_car_purchases = mysqli_fetch_assoc($second_hand_car_purchases_result);

                // Total purchases (new + second-hand)
                $total_purchases = $new_car_purchases['total_new_purchases'] + $second_hand_car_purchases['total_second_hand_purchases'];
                echo $total_purchases;
                ?>
            </p>
        </div>

        <!-- Box 4: Total Income -->
        <div class="box dash_box">
            <h3>Total Income</h3>
            <p>
                <?php
                // Fetch total income from new car sales ('car_payment' table) by joining with 'cars' table to get price
                $new_car_income_sql = "SELECT SUM(c.price) AS total_new_income 
                                      FROM car_payment cp 
                                      JOIN cars c ON cp.car_id = c.id 
                                      WHERE cp.payment_status = 'completed'";
                $new_car_income_result = mysqli_query($conn, $new_car_income_sql);
                $new_car_income = mysqli_fetch_assoc($new_car_income_result);

                // Fetch total income from second-hand car sales ('second_car_purchase' table) by joining with 'second_hand_cars' table to get price
                $second_hand_car_income_sql = "SELECT SUM(shc.price) AS total_second_hand_income 
                                              FROM second_car_purchase sc 
                                              JOIN second_hand_cars shc ON sc.car_id = shc.id";
                $second_hand_car_income_result = mysqli_query($conn, $second_hand_car_income_sql);
                $second_hand_car_income = mysqli_fetch_assoc($second_hand_car_income_result);

                // Total income (new + second-hand)
                $total_income = $new_car_income['total_new_income'] + $second_hand_car_income['total_second_hand_income'];
                echo "₹" . number_format($total_income, 2);
                ?>
            </p>
        </div>

    </div>
</div>




     
<!-- Cars Section -->
<div id="cars" class="section">
    <h1>Car Purchases</h1>
    <p>Manage new and second-hand car purchases here.</p>

    <!-- Cards for Total Purchases -->
    <div class="cards-section" style="display: flex; justify-content: space-between; margin-bottom: 20px;">
        <!-- Card 1: Total New Car Purchases -->
        <div class="card" style="flex: 1; margin-right: 10px; padding: 20px; background-color: #f4f4f4; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <h3>Total New Car Purchases</h3>
            <p style="font-size: 24px; font-weight: bold; color: #4CAF50;">
                <?php
                // Fetch total new car purchases count
                $new_car_count_sql = "SELECT COUNT(*) AS total_new_cars FROM car_payment";
                $new_car_count_result = mysqli_query($conn, $new_car_count_sql);
                $new_car_count = mysqli_fetch_assoc($new_car_count_result);
                echo $new_car_count['total_new_cars'];
                ?>
            </p>
        </div>

        <!-- Card 2: Total Second Hand Car Purchases -->
        <div class="card" style="flex: 1; margin-left: 10px; padding: 20px; background-color: #f4f4f4; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <h3>Total Second Hand Car Purchases</h3>
            <p style="font-size: 24px; font-weight: bold; color: #f44336;">
                <?php
                // Fetch total second-hand car purchases count
                $second_hand_car_count_sql = "SELECT COUNT(*) AS total_second_hand_cars FROM second_car_purchase";
                $second_hand_car_count_result = mysqli_query($conn, $second_hand_car_count_sql);
                $second_hand_car_count = mysqli_fetch_assoc($second_hand_car_count_result);
                echo $second_hand_car_count['total_second_hand_cars'];
                ?>
            </p>
        </div>
    </div>

    <!-- Car Purchase Overview (Tables Below Cards) -->
    <div class="tables-section">

        <!-- Left Div: New Cars Purchases -->
        <div class="left-div">
            <h2>New Car Purchases</h2>
            <table class="car-table">
                <thead>
                    <tr>
                        <th>SR No.</th>
                        <th>Car Name</th>
                        <th>Model Year</th>
                        <th>Price</th>
                        <th>Buyer Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Payment Status</th>
                        <th>Payment Date</th>
                        <th>Actions</th> <!-- New column for actions -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch new car purchase data from car_payment table
                    $new_car_sql = "SELECT cp.*, c.name AS car_name, c.model_year, c.price 
                                    FROM car_payment cp
                                    JOIN cars c ON cp.car_id = c.id";
                    $new_car_result = mysqli_query($conn, $new_car_sql);

                    if (mysqli_num_rows($new_car_result) > 0) {
                        $sr_no = 1;
                        while ($row = mysqli_fetch_assoc($new_car_result)) {
                            echo '<tr>';
                            echo '<td>' . $sr_no++ . '</td>';
                            echo '<td>' . htmlspecialchars($row['car_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['model_year']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['price']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['payment_status']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['payment_date']) . '</td>';
                            echo '<td>
                                    <button class="confirm-btn">Confirm</button>
                                    <button class="cancel-btn">Cancel</button>
                                  </td>';  // Added action buttons
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="10">No new car purchases found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Right Div: Second Hand Cars Purchases -->
        <div class="right-div">
            <h2>Second Hand Car Purchases</h2>
            <table class="car-table">
                <thead>
                    <tr>
                        <th>SR No.</th>
                        <th>Car Name</th>
                        <th>Buyer Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Payment Method</th>
                        <th>Billing Address</th>
                        <th>Purchase Date</th>
                        <th>Actions</th> <!-- New column for actions -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch second-hand car purchase data from second_car_purchase table
                    $second_hand_car_sql = "SELECT shp.*, c.name AS car_name 
                                            FROM second_car_purchase shp
                                            JOIN second_hand_cars c ON shp.car_id = c.id";
                    $second_hand_car_result = mysqli_query($conn, $second_hand_car_sql);

                    if (mysqli_num_rows($second_hand_car_result) > 0) {
                        $sr_no = 1;
                        while ($row = mysqli_fetch_assoc($second_hand_car_result)) {
                            echo '<tr>';
                            echo '<td>' . $sr_no++ . '</td>';
                            echo '<td>' . htmlspecialchars($row['car_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['buyer_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['buyer_email']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['buyer_phone']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['buyer_address']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['payment_method']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['billing_address']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
                            echo '<td>
                                    <button class="confirm-btn">Confirm</button>
                                    <button class="cancel-btn">Cancel</button>
                                  </td>';  // Added action buttons
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="10">No second-hand car purchases found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>




        <!-- Users Section -->
        <div id="users" class="section">
            <h1>Users</h1>
            <p>Manage user information and permissions here.</p>
            <table id="usersTable" class="user-table">
                <thead>
                    <tr>
                        <th>SR No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <tr class="no-data">
                        <td colspan="6">No user data available.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Reviews Section -->
        <div id="reviews" class="section">
            <h1>Reviews</h1>
            <p>Manage customer reviews and feedback here.</p>

            <div class="review-summary">
                <div class="review-card left">
                    <img src="assets/happy.jpg" alt="Happy Expression" class="icon">
                    <h3>Happy Customers</h3>
                    <p id="happyCount" class="count">0</p>
                </div>
                <div class="review-card center">
                    <img src="assets/modrate.jpg" alt="Moderate Expression" class="icon">
                    <h3>Moderate Customers</h3>
                    <p id="moderateCount" class="count">0</p>
                </div>
                <div class="review-card right">
                    <img src="assets/unhappy.jpg" alt="Unhappy Expression" class="icon">
                    <h3>Unhappy Customers</h3>
                    <p id="unhappyCount" class="count">0</p>
                </div>
            </div>

            <table id="reviewsTable" class="reviews-table">
                <thead>
                    <tr>
                        <th>SR No.</th>
                        <th>Customer Name</th>
                        <th>Review</th>
                        <th>Rating</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody id="reviewsTableBody">
                    <tr class="no-data">
                        <td colspan="5">No reviews available.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Add New Cars Section -->
        <div id="addCars" class="section">
            <h1>Add New Cars</h1>
            <form action="add_car.php" method="POST" enctype="multipart/form-data">
                <label for="category">Category:</label>
                <select name="category" id="category">
                    <option value="SUV">SUV</option>
                    <option value="Sedan">Sedan</option>
                    <option value="Hatchback">Hatchback</option>
                    <option value="Pickuptruck">Pickup Truck</option>
                    <option value="Convertible">Convertible</option>
                    <option value="Luxury">Luxury</option>
                </select>

                <label for="name">Car Name:</label>
                <input type="text" name="name" id="name" required>

                <label for="model_year">Model Year:</label>
                <input type="number" name="model_year" id="model_year" required>

                <label for="price">Price:</label>
                <input type="number" name="price" id="price" required>

                <label for="image">Car Image:</label>
                <input type="file" name="image" id="image" required> 

                <button type="submit">Add Car</button>
            </form>

            <form method="POST" action="admin_dashboard.php" id="categoryFilterForm">
                <label for="category_filter">Filter by Category:</label>
                <select name="category_filter" id="category_filter" onchange="document.getElementById('categoryFilterForm').submit()">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category; ?>" <?php echo ($category_filter == $category) ? 'selected' : ''; ?>><?php echo $category; ?></option>
                    <?php endforeach; ?>
                </select>
            </form>

            <h2>Added Cars</h2>
<table class="car-table">
    <thead>
        <tr>
            <th>SR No.</th>
            <th>Car Name</th>
            <th>Category</th>
            <th>Model Year</th>
            <th>Price</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM cars";
        if ($category_filter) {
            $sql .= " WHERE category = '$category_filter'";
        }

        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $sr_no = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $sr_no++ . '</td>';
                echo '<td>' . htmlspecialchars($row['name'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($row['category'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($row['model_year'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($row['price'] ?? '') . '</td>';
                echo '<td><img src="assets/images/' . htmlspecialchars($row['image'] ?? '') . '" width="50"></td>';
                echo '<td>
                        <a href="edit_car.php?id=' . $row['id'] . '">Edit</a> | 
                        <a href="?delete_new_car_id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this car?\')">Delete</a>
                      </td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="7">No cars found.</td></tr>';
        }
        ?>
    </tbody>
</table>

        </div>

        <!-- Second Hand Cars Section -->
        <div id="secondhandcars" class="section">
            <h1>Second Hand Cars</h1>
            <p>Configure your second-hand car details here.</p>

            <!-- Form to add second-hand car -->
            <form action="admin_dashboard.php" method="POST" enctype="multipart/form-data">
                <label for="category">Category:</label>
                <select name="category" id="category" required>
                    <option value="SUV">SUV</option>
                    <option value="Sedan">Sedan</option>
                    <option value="Hatchback">Hatchback</option>
                    <option value="Pickuptruck">Pickup Truck</option>
                    <option value="Convertible">Convertible</option>
                    <option value="Luxury">Luxury</option>
                </select>

                <label for="name">Car Name:</label>
                <input type="text" name="name" id="name" required>

                <label for="model_year">Model Year:</label>
                <input type="number" name="model_year" id="model_year" required>

                <label for="condition">Condition:</label>
                <input type="text" name="condition" id="condition" required>

                <label for="price">Price:</label>
                <input type="number" name="price" id="price" required>

                <label for="kilometer">Kilometer:</label>
                <input type="number" name="kilometer" id="kilometer" required>

                <label for="image">Car Image:</label>
                <input type="file" name="image" id="image" accept="image/*" required> <!-- Only allow image files -->

                <button type="submit" name="submit">Add Car</button>
            </form>

            <h2>Second-Hand Cars</h2>
            <table class="car-table">
                <thead>
                    <tr>
                        <th>SR No.</th>
                        <th>Car Name</th>
                        <th>Category</th>
                        <th>Model Year</th>
                        <th>Price</th>
                        <th>Condition</th>
                        <th>Kilometer</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch second-hand cars from the database
                    $second_hand_sql = "SELECT * FROM second_hand_cars";
                    $second_hand_result = mysqli_query($conn, $second_hand_sql);
                    if (mysqli_num_rows($second_hand_result) > 0) {
                        $sr_no = 1;
                        while ($row = mysqli_fetch_assoc($second_hand_result)) {
                            echo '<tr>';
                            echo '<td>' . $sr_no++ . '</td>';
                            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['category']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['model_year']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['price']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['condition']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['kilometer']) . '</td>';
                            echo '<td><img src="assets/images/' . htmlspecialchars($row['image']) . '" width="50"></td>';
                            echo '<td>
                                    <a href="edit_car.php?id=' . $row['id'] . '">Edit</a> | 
                                    <a href="?delete_id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this car?\')">Delete</a>
                                  </td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="9">No second-hand cars found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Contact Us Section -->
        <div id="contact" class="section">
            <h1>Contact Us Entries</h1>
            <div class="summary-card">
                <h3>Total Contact Us Entries</h3>
                <?php
                // Fetch the total count of contact us entries
                $count_sql = "SELECT COUNT(*) AS total FROM contact_us";
                $count_result = mysqli_query($conn, $count_sql);
                $total_count = mysqli_fetch_assoc($count_result)['total'];
                ?>
                <p><?php echo $total_count; ?> Entries</p>
            </div>

            <table class="contact-table">
                <thead>
                    <tr>
                        <th>SR No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch all entries from the contact_us table
                    $contact_sql = "SELECT * FROM contact_us ORDER BY submitted_at DESC";
                    $contact_result = mysqli_query($conn, $contact_sql);

                    if (mysqli_num_rows($contact_result) > 0) {
                        $sr_no = 1;
                        while ($row = mysqli_fetch_assoc($contact_result)) {
                            echo '<tr>';
                            echo '<td>' . $sr_no++ . '</td>';
                            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['message']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['submitted_at']) . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6">No contact entries found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>


        <script src="js/admin_panel.js"></script>
    </div>
</body>
</html>
