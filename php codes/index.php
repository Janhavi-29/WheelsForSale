<?php
session_start(); // Start the session
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Scroll Navigation with Video</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="icon" href="assets/nex.png" type="image/x-icon">
   
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="logo" style="color: red;">DriveNex.com</div>
      <ul class="nav-links">
        <li><a href="#home">Home</a></li>
        <li><a href="#buycar">Buy Car</a></li>
        <li><a href="#secondhandedcars">Second Handed Cars</a></li>
        <li><a href="#reviews">Reviews</a></li>
        <li><a href="#oursells">Our Sells</a></li>
        <li><a href="#about">About Us</a></li>
        <li><a href="#contact-us">Contact Us</a></li>
      </ul>
    </nav>
  </header>
  <main>
    <!----------------------------------------------------------------------------------->

    <section id="home">
        <video autoplay muted loop id="background-video">
            <source src="assets/bgvideo.mp4" type="video/mp4">
        </video>

        <!-- Centered Content (Website Name, Welcome, and Description) -->
        <div class="home-content">
            <h1>Welcome to DriveNex</h1>
            <p>Your ultimate destination for seamless driving experiences.</p>
        </div>

        <!-- Platform Icons - Left Bottom Corner -->
        <div class="platform-icons">
            <a href="https://facebook.com" target="_blank">
                <ion-icon name="logo-facebook"></ion-icon>
            </a>
            <a href="https://twitter.com" target="_blank">
                <ion-icon name="logo-twitter"></ion-icon>
            </a>
            <a href="https://instagram.com" target="_blank">
                <ion-icon name="logo-instagram"></ion-icon>
            </a>
            <a href="https://linkedin.com" target="_blank">
                <ion-icon name="logo-linkedin"></ion-icon>
            </a>
            <a href="https://youtube.com" target="_blank">
                <ion-icon name="logo-youtube"></ion-icon>
            </a>
        </div>

        <!-- Auth Buttons - Right Bottom Corner -->
        <div class="auth-buttons">
            <?php if (isset($_SESSION['name'])): ?>
                <a href="logout.php">
                    <button id="logout-button" class="red-btn">Logout</button>
                </a>
            <?php else: ?>
                <a href="user_signup.php">
                    <button id="signup-button" class="green-btn">Sign Up</button>
                </a>
                <a href="user_signin.php">
                    <button id="signin-button" class="blue-btn">Sign In</button>
                </a>
            <?php endif; ?>
        </div>
    </section>
    

    
    

    <!----------------------------------------------------------------------------------->

    <section id="buycar">
  <h2 class="section-title">Available Categories</h2>
  <div class="card-container">
    <!-- SUV Category -->
    <div class="card">
      <img src="assets/SUV.png" alt="SUV">
      <div class="card-content">
        <p style="color: red;">SUV</p>
        <button class="buy-btn" onclick="checkSignIn(event, 'category.php?category=SUV')">Explore</button>
      </div>
    </div>

    <!-- Sedan Category -->
    <div class="card">
      <img src="assets/Sedan.png" alt="Sedan">
      <div class="card-content">
        <p style="color: red;">Sedan</p>
        <button class="buy-btn" onclick="checkSignIn(event, 'category.php?category=Sedan')">Explore</button>
      </div>
    </div>

    <!-- Hatchback Category -->
    <div class="card">
      <img src="assets/Hatchback.png" alt="Hatchback">
      <div class="card-content">
        <p style="color: red;">Hatchback</p>
        <button class="buy-btn" onclick="checkSignIn(event, 'category.php?category=Hatchback')">Explore</button>
      </div>
    </div>

    <!-- Pickup Truck Category -->
    <div class="card">
      <img src="assets/Pickuptruck.png" alt="Pickup Truck">
      <div class="card-content">
        <p style="color: red;">Pickup Truck</p>
        <button class="buy-btn" onclick="checkSignIn(event, 'category.php?category=Pickuptruck')">Explore</button>
      </div>
    </div>

    <!-- Convertible Category -->
    <div class="card">
      <img src="assets/Convertible.png" alt="Convertible">
      <div class="card-content">
        <p style="color: red;">Convertible</p>
        <button class="buy-btn" onclick="checkSignIn(event, 'category.php?category=Convertible')">Explore</button>
      </div>
    </div>

    <!-- Luxury Category -->
    <div class="card">
      <img src="assets/luxury.png" alt="Luxury">
      <div class="card-content">
        <p style="color: red;">Luxury</p>
        <button class="buy-btn" onclick="checkSignIn(event, 'category.php?category=Luxury')">Explore</button>
      </div>
    </div>
  </div>
</section>

<script>
  function navigateToCategory(category) {
    // Redirect to the category page dynamically based on the selected category
    window.location.href = `category.php?category=${encodeURIComponent(category)}`;
  }
</script>



<!----------------------------------------------------------------------------------->

<section id="secondhandedcars">
  <div class="best-week-sales">
    <p>Best Week Sales</p>
  </div>

  <div class="car-gallery">
    <?php
    // Include database connection
    include('connection.php');

    // Fetch all second-hand cars from the database
    $sql = "SELECT * FROM second_hand_cars";
    $result = $conn->query($sql);

    // Check if there are any cars in the database
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Get car details
            // Ensure the correct image path (assume images are stored in 'assets/images/')
            $image = $row['image'] ? 'assets/images/' . $row['image'] : 'assets/images/default_car_image.jpg'; // Fallback image if not available
            $name = $row['name'];
            $model_year = $row['model_year'];
            $kilometer = $row['kilometer'];
            $car_id = $row['id']; // Assuming 'id' is the unique identifier for each car

            // Output the car card dynamically
            echo "
            <div class='car-box'>
                <img src='$image' alt='Car Image' class='car-image'>
                <div class='car-details'>
                    <ul>
                        <li><strong>Model:</strong> $name</li>
                        <li><strong>Year:</strong> $model_year</li>
                        <li><strong>Kilometer:</strong> $kilometer km</li>
                    </ul>
                </div>
                <button class='buy-btn' onclick=\"checkSignIn(event, 'secondhand_payment.php?car_id={$car_id}')\">Buy Now</button>
            </div>";
        }
    } else {
        echo "<p>No second-hand cars available.</p>";
    }

    // Close database connection
    $conn->close();
    ?>
  </div>

  <div class="navigation-buttons">
    <button class="prev-btn">Previous</button>
    <button class="next-btn">Next</button>
  </div>
</section>





    <!----------------------------------------------------------------------------------->

    <section id="reviews">
      <div class="reviews-container">
        <!-- Left Div with Customer Reviews -->
        <div class="reviews-left">
          <div class="reviews-box">
            <h2>Customer Reviews</h2>
            <ul class="reviews-list">
              <li>
                <strong>John Doe</strong>
                <p>"Great service, highly recommend it!"</p>
              </li>
              <li>
                <strong>Jane Smith</strong>
                <p>"Very professional and efficient, will use again!"</p>
              </li>
              <li>
                <strong>Robert Brown</strong>
                <p>"Fantastic experience from start to finish!"</p>
              </li>
              <li>
                <strong>Emily Davis</strong>
                <p>"Excellent customer service, will return!"</p>
              </li>
              <li>
                <strong>Michael Lee</strong>
                <p>"Best experience I've had in a long time."</p>
              </li>
              <li>
                <strong> David Williams</strong>
                <p>"Top-notch service, efficient, and exactly what I needed from start to finish!"</p>
              </li>
            </ul>
          </div>
        </div>
    
        <!-- Right Div with Team Reviews -->
        <div class="reviews-right">
          <h2 style="margin-top: 6%;padding-bottom: 50px;color: red;">What Our Team Says</h2>
          <div class="review-box">
            <img src="assets/person1.jpeg" alt="Profile 1" class="profile-img">
            <h3>Raj Kothari</h3>
            <p>"We are proud of our team's dedication to providing the best service."</p>
          </div>
          <div class="review-box">
            <img src="assets/person2.jpeg" alt="Profile 2" class="profile-img">
            <h3>Sophia Green</h3>
            <p>"Teamwork and commitment are at the core of everything we do."</p>
          </div>
          <div class="review-box">
            <img src="assets/person3.jpeg" alt="Profile 3" class="profile-img">
            <h3>Janhavi Joshi</h3>
            <p>"We work together to ensure a seamless experience for our clients."</p>
          </div>
        </div>
      </div>
    </section>
    
  

    <!----------------------------------------------------------------------------------->

    <section id="oursells">
      <h1 class="sales-heading">Our Amazing Sales</h1>
    
      <div class="sells-container">
        <div class="sells-column">
          <div class="image-wrapper">
            <img src="assets/oursells1.jpg" alt="Cars for Sale" class="column-image">
          </div>
          <h2>CARS FOR SALE</h2>
          <p class="count">82.000</p>
        </div>
    
        <div class="sells-column">
          <div class="image-wrapper">
            <img src="assets/oursells2.jpeg" alt="Happy Customers" class="column-image">
          </div>
          <h2>Happy Customers</h2>
          <p class="count">19.500</p>
        </div>
    
        <div class="sells-column">
          <div class="image-wrapper">
            <img src="assets/oursells3.webp" alt="Dealer Reviews" class="column-image">
          </div>
          <h2>DEALER REVIEWS</h2>
          <p class="count">6.500</p>
        </div>
    
        <div class="sells-column">
          <div class="image-wrapper">
            <img src="assets/oursells4.webp" alt="Visitors Per Day" class="column-image">
          </div>
          <h2>VISITORS PER DAY</h2>
          <p class="count">1.000</p>
        </div>
    
        <div class="sells-column">
          <div class="image-wrapper">
            <img src="assets/oursells5.avif" alt="Verified Dealers" class="column-image">
          </div>
          <h2>VERIFIED DEALERS</h2>
          <p class="count">5.000</p>
        </div>
      </div>
    </section>
    
    


    <!----------------------------------------------------------------------------------->

    <section id="about" class="about-section">
      <div class="about-left">
        <!-- About Us Title -->
        <h2 class="about-title">About Us</h2>
        
        <!-- Website Information -->
        <div class="website-info">
          <p>Welcome to our website! We provide the best car buying and selling experience. Our platform offers a range of services that help you find the perfect vehicle with ease. We aim to connect buyers with sellers efficiently while offering a seamless and secure process. Whether you are looking to buy your dream car or sell your current vehicle, we ensure a smooth transaction every time.</p>
          
          <p>Our team is dedicated to offering the best customer service and ensuring the utmost satisfaction with every purchase. With a strong commitment to quality, we provide a transparent process for all transactions, backed by years of experience in the automotive industry.</p>
        </div>
        
        <!-- Read More Button -->
        <button class="read-more-btn" onclick="openPopup()">Read More</button>
        
        <!-- Popup for Read More -->
        <div class="popup" id="about-popup">
          <div class="popup-content">
            <span class="close-btn" onclick="closePopup()">&times;</span>
            <h3>More About Us</h3>
            <p>We take pride in our work and always put our customers first. Our mission is to create a trusted environment for buying and selling cars, ensuring each transaction is smooth, safe, and satisfactory. Whether you are a first-time buyer or a seasoned car enthusiast, our team is here to guide you through every step of the way.</p>
            
            <p>With a wide selection of vehicles, various financing options, and a customer-first approach, we aim to redefine the car buying experience. Stay with us, and let's make your next car purchase an unforgettable one!</p>
            
            <!-- Back to Website Button -->
            <button class="back-btn" onclick="backToWebsite()">Back to Website</button>
          </div>
        </div>
      </div>
    
      <!-- Right Div (empty for now) -->
      <div class="right-div">
        <div class="square-container">
          <img src="assets/aboutus.jpg" alt="Image" class="square-image">
          <div class="square"></div>
        </div>
      </div>
      
    </section>
    

    <!----------------------------------------------------------------------------------->
    
    <section id="contact-us">
  <h2>Contact Us</h2>
  <p>Feel free to get in touch with us. We're here to help!</p>

  <!-- Contact Us Button -->
  <button id="contact-us-btn" onclick="openPopup('contact-us-popup')">Contact Us</button>

  <!-- Add Review Button -->
  <button id="add-review-btn" class="add-review-btn" onclick="openPopup('add-review-popup')">Add Review</button>

  <!-- The movable box -->
  <div id="movable-box">
    <p>This is the movable box!</p>
  </div>

  <div id="movable-box">
    <!-- Left Section -->
    <div class="box-section left">
      <h2>DRIVENEX</h2>
      <p style="margin-left: 40px;">Main Office: 1234 Street Name, City, Country</p>
      <p style="margin-left: -85px;">Email: contact@drivenex.com</p>
      <p style="margin-left: -140px;">Phone: +123 456 7890</p>
    </div>

    <!-- Center Section -->
    <div class="box-section center">
      <h3>Top Brands</h3>
      <div class="brands-lists">
        <ul class="brand-list">
          <li>Toyota</li>
          <li>Mercedes-Benz</li>
          <li>BMW</li>
          <li>Audi</li>
          <li>Lamborghini</li>
          <li>Ford</li>
        </ul>
        <ul class="brand-list">
          <li>Honda</li>
          <li>Porsche</li>
          <li>Lexus</li>
          <li>Chevrolet</li>
          <li>Nissan</li>
          <li>Volkswagen</li>
        </ul>
      </div>
    </div>

    <!-- Right Section -->
    <div class="box-section right">
      <h3>Subscribe</h3>
      <textarea class="email-input" placeholder="Enter your email"></textarea>
      <button class="subscribe-btn">Subscribe</button>
    </div>
  </div>
</section>

<!-- Contact Us Form Popup -->
<div id="contact-us-popup" class="popup">
  <div class="popup-content">
    <span class="close-btn" onclick="closePopup('contact-us-popup')">&times;</span>
    <h2>Contact Us</h2>
    <form id="contact-form" method="POST" action="contact-us-submit.php">
      <input type="text" id="name" name="name" placeholder="Your Name" required />
      <input type="email" id="email" name="email" placeholder="Your Email" required />
      <input type="tel" id="phone" name="phone" placeholder="Your Phone No" required />
      <textarea id="message" name="message" placeholder="Your Message" required></textarea>
      <button type="submit" class="submit-btn">Submit</button>
      <!-- Back Button -->
      <button type="button" class="back-btn" onclick="closePopup('contact-us-popup'); scrollToSection('contact-us');">Back</button>
    </form>
  </div>
</div>

<!-- Add Review Form Popup -->
<div id="add-review-popup" class="popup">
  <div class="popup-content">
    <span class="close-btn" onclick="closePopup('add-review-popup')">&times;</span>
    <h2>Add Review</h2>
    <form id="add-review-form" method="POST" action="add-review-submit.php">
      <input type="text" id="name" name="name" placeholder="Your Name" required />
      <input type="email" id="email" name="email" placeholder="Your Email" required />
      <textarea id="review" name="review" placeholder="Your Review" required></textarea>

      <!-- Star Rating System -->
      <div class="rating">
        <label for="rating">Rating:</label>
        <span class="star" data-value="1">&#9733;</span>
        <span class="star" data-value="2">&#9733;</span>
        <span class="star" data-value="3">&#9733;</span>
        <span class="star" data-value="4">&#9733;</span>
        <span class="star" data-value="5">&#9733;</span>
        <input type="hidden" name="rating" id="rating" required />
      </div>

      <button type="button" class="back-btn" onclick="closePopup('add-review-popup')">Back</button>
      <button type="submit" class="submit-btn">Submit</button>
    </form>
  </div>
</div>


    
    

    <!----------------------------------------------------------------------------------->
  </main>
  <script src="js/script.js"></script>
  <script src="js/checkLogin.js"></script>
</body>

</html>