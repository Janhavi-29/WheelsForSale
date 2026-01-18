// Smooth scroll for navigation links
document.querySelectorAll('.nav-links a').forEach(link => {
  link.addEventListener('click', (e) => {
    e.preventDefault();
    const targetId = link.getAttribute('href');
    const targetSection = document.querySelector(targetId);
    window.scrollTo({
      top: targetSection.offsetTop,
      behavior: 'smooth'
    });
  });
});

// Track the currently visible range of car boxes
let currentIndex = 0;  // Keep track of the first visible car box

// Function to update the visibility of car boxes based on currentIndex
function updateCarBoxes() {
  const carBoxes = document.querySelectorAll('.car-box');
  carBoxes.forEach(box => box.style.display = 'none'); // Hide all car boxes

  for (let i = currentIndex; i < currentIndex + 3; i++) {
    if (carBoxes[i]) {
      carBoxes[i].style.display = 'block'; // Show 3 boxes starting from currentIndex
    }
  }
}

// Initialize the car gallery by showing the first 3 boxes
updateCarBoxes();

// Next button functionality
document.querySelector('.next-btn').addEventListener('click', () => {
  const carBoxes = document.querySelectorAll('.car-box');
  currentIndex++;
  if (currentIndex + 3 > carBoxes.length) currentIndex = 0; // Reset if exceeding
  updateCarBoxes();
});

// Previous button functionality
document.querySelector('.prev-btn').addEventListener('click', () => {
  const carBoxes = document.querySelectorAll('.car-box');
  currentIndex--;
  if (currentIndex < 0) currentIndex = carBoxes.length - 3; // Reset to last 3
  updateCarBoxes();
});

// Popup functionality for About section
let popupOpened = false; // Flag to track if popup is already opened

function openPopup() {
  if (!popupOpened) { // Check if the popup is already open
    document.getElementById('about-popup').style.display = 'flex';
    popupOpened = true;  // Set flag to prevent reopening
  }
}

function closePopup() {
  document.getElementById('about-popup').style.display = 'none';
  popupOpened = false; // Reset the flag when closing
}

function backToWebsite() {
  document.getElementById('about-popup').style.display = 'none';
  const aboutSection = document.getElementById('about');
  window.scrollTo({
    top: aboutSection.offsetTop,
    behavior: 'smooth'
  });
}

// Modal functionality for Contact Us section
const modal = document.getElementById("contact-us-popup");
const btn = document.getElementById("contact-us-btn");
const closeBtn = document.querySelector("#contact-us-popup .close-btn");
const backBtn = document.querySelector("#contact-us-popup .back-btn");
const contactSection = document.getElementById("contact-us");

// Open the Contact Us modal
btn.onclick = () => { modal.style.display = "flex"; };

// Close the modal on close button click
closeBtn.onclick = () => { modal.style.display = "none"; };

// Back button functionality for Contact Us
backBtn.onclick = () => {
  modal.style.display = "none";
  window.scrollTo({
    top: contactSection.offsetTop,
    behavior: "smooth"
  });
};

// Close modal if clicking outside it
window.onclick = (event) => {
  if (event.target == modal) modal.style.display = "none";
};

// Add Review popup functionality
document.getElementById('add-review-btn').addEventListener('click', () => {
  document.getElementById('add-review-popup').style.display = 'flex'; // Open Add Review popup
});

// Close Add Review popup on any close button
document.querySelectorAll('#add-review-popup .close-btn').forEach(button => {
  button.addEventListener('click', () => {
    document.getElementById('add-review-popup').style.display = 'none'; // Close Add Review popup
  });
});

// Add Review back button functionality
document.querySelector('#add-review-popup .back-btn').addEventListener('click', () => {
  document.getElementById('add-review-popup').style.display = 'none';
});

// Star rating functionality for Add Review
const stars = document.querySelectorAll('#add-review-popup .star');
stars.forEach(star => {
  star.addEventListener('click', () => {
    stars.forEach(s => s.classList.remove('selected')); // Reset selection
    star.classList.add('selected'); // Highlight clicked star
    const index = Array.from(stars).indexOf(star); // Get index of clicked star
    for (let i = 0; i <= index; i++) {
      stars[i].classList.add('selected'); // Highlight all stars up to the clicked one
    }
    document.getElementById('rating').value = index + 1; // Set the rating value
  });
});

// Form submission for Add Review using AJAX
document.getElementById('add-review-form').addEventListener('submit', function(event) {
  event.preventDefault(); // Prevent default form submission behavior

  const formData = new FormData(this); // Get form data

  // Send the form data to the server using AJAX
  fetch('add-review-submit.php', {
    method: 'POST',
    body: formData
  })
    .then(response => response.json()) // Parse the JSON response
    .then(data => {
      if (data.status === 'success') {
        alert(data.message); // Show success message
        this.reset(); // Reset the form fields
        document.getElementById('add-review-popup').style.display = 'none'; // Close the popup
        
        // Scroll to the section where the review is added
        const reviewSection = document.getElementById('contact-us');
        window.scrollTo({
          top: reviewSection.offsetTop,
          behavior: 'smooth'
        });
      } else {
        alert(data.message); // Show error message
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert("An error occurred. Please try again later.");
    });
});

// Form submission for Contact Us using AJAX
document.getElementById('contact-form').addEventListener('submit', function(event) {
  event.preventDefault(); // Prevent default form submission behavior

  const formData = new FormData(this);

  fetch('submit_contact.php', {
    method: 'POST',
    body: formData
  })
    .then(response => response.text())
    .then(data => {
      if (data === "success") {
        alert("Your contact form has been submitted successfully!");
        this.reset(); // Reset the form fields
        modal.style.display = "none"; // Close the modal
        window.scrollTo({
          top: contactSection.offsetTop,
          behavior: 'smooth'
        });
      } else {
        alert("There was an error submitting your form. Please try again.");
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert("An error occurred. Please try again later.");
    });
});

// View More button for About Us section
document.getElementById('view-more-btn').addEventListener('click', () => {
  document.getElementById('about-popup').style.display = 'flex'; // Open About Us popup
});

// Close About Us popup
document.querySelector('#about-popup .close-btn').addEventListener('click', () => {
  document.getElementById('about-popup').style.display = 'none'; // Close About Us popup
});

// Add new car dynamically to the gallery
function addCar(model, year, kilometers, imageUrl, carDetailsPageUrl) {
  const carGallery = document.querySelector('.car-gallery');

  // Create car box div element
  const carBox = document.createElement('div');
  carBox.classList.add('car-box');

  // Add car image
  const carImage = document.createElement('img');
  carImage.src = imageUrl;
  carImage.alt = `Car Image of ${model}`;
  carBox.appendChild(carImage);

  // Create car details container
  const carDetails = document.createElement('div');
  carDetails.classList.add('car-details');

  const carInfoList = document.createElement('ul');
  const modelItem = document.createElement('li');
  modelItem.innerHTML = `<strong>Model:</strong> ${model}`;
  const yearItem = document.createElement('li');
  yearItem.innerHTML = `<strong>Year:</strong> ${year}`;
  const kmItem = document.createElement('li');
  kmItem.innerHTML = `<strong>Kilometer:</strong> ${kilometers} km`;

  carInfoList.appendChild(modelItem);
  carInfoList.appendChild(yearItem);
  carInfoList.appendChild(kmItem);
  carDetails.appendChild(carInfoList);

  // Create Buy Now button
  const buyButton = document.createElement('button');
  buyButton.classList.add('buy-btn');
  buyButton.innerText = 'Buy Now';
  buyButton.setAttribute('onclick', `checkSignIn(event, '${carDetailsPageUrl}')`);

  carBox.appendChild(carDetails);
  carBox.appendChild(buyButton);

  // Append the new car box to the gallery
  carGallery.appendChild(carBox);

  // After inserting, trigger a layout update
  window.getComputedStyle(carBox).width; // Force reflow
}

// Check if the user is logged in before allowing access to car details or purchase page
function checkSignIn(event, targetUrl) {
  if (!isLoggedIn) {
    // Prevent default action (navigation) and show alert
    event.preventDefault();
    alert("Please sign up or sign in first to access this section.");
  } else {
    // If logged in, allow navigation
    window.location.href = targetUrl;
  }
}

// Example call to add a new car dynamically
addCar('Ford Mustang', '2022', '5,000', 'assets/secondhand8.jpg', 'car8.html');
