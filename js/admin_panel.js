// Show Section
function showSection(sectionId) {
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => section.classList.remove('active'));

    const selectedSection = document.getElementById(sectionId);
    if (selectedSection) {
        selectedSection.classList.add('active');

        // Fetch data when the respective section is shown
        switch (sectionId) {
            case 'reviews':
                fetchReviews();
                fetchReviewSummary();  // Fetch review summary when the reviews section is shown
                break;
            case 'users':
                fetchUsers();
                break;
            case 'contact':
                fetchContact();
                break;
            case 'secondhandcars':
                fetchSecondHandCars(); // Added case for second-hand cars
                break;
            default:
                console.error('Invalid section ID:', sectionId);
        }
    }
}

// Fetch Second-Hand Cars and Populate Cards
function fetchSecondHandCars() {
    // Ensure the section stays on the current page, no redirection
    fetch('fetch_secondhand_cars.php') // This assumes you have a PHP file that returns the second-hand cars data as JSON
        .then(response => response.json())
        .then(data => {
            const carCardsContainer = document.getElementById('carCardsContainer');
            carCardsContainer.innerHTML = ''; // Clear existing cards

            if (data.length === 0) {
                carCardsContainer.innerHTML = '<p>No second-hand cars available.</p>';
            } else {
                data.forEach((car, index) => {
                    const card = document.createElement('div');
                    card.classList.add('car-card');
                    card.innerHTML = `
                        <div class="car-image-container">
                            ${car.image ? `<img src="${car.image}" alt="Car Image" class="car-image">` : 'No Image'}
                        </div>
                        <div class="car-details">
                            <h3>${car.name}</h3>
                            <p><strong>Category:</strong> ${car.category}</p>
                            <p><strong>Model Year:</strong> ${car.model_year}</p>
                            <p><strong>Condition:</strong> ${car.condition}</p>
                            <p><strong>Price:</strong> $${car.price}</p>
                            <p><strong>Kilometer:</strong> ${car.kilometer} km</p> <!-- Added kilometer field -->
                        </div>
                    `;
                    carCardsContainer.appendChild(card);
                });
            }
        })
        .catch(error => console.error('Error fetching second-hand cars:', error));
}

// Fetch Reviews and Populate Table
function fetchReviews() {
    fetch('fetch_reviews.php') // Fetch data from the `reviews` table
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('reviewsTableBody');
            tableBody.innerHTML = ''; // Clear existing rows

            if (data.length === 0) {
                tableBody.innerHTML = '<tr class="no-data"><td colspan="5">No reviews available.</td></tr>';
            } else {
                data.forEach((review, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${review.name}</td>
                        <td>${review.review}</td>
                        <td>${review.rating}</td>
                        <td>${review.submitted_at}</td>
                    `;
                    tableBody.appendChild(row);
                });
            }
        })
        .catch(error => console.error('Error fetching reviews:', error));
}

// Fetch Review Summary (Happy, Moderate, Unhappy Count)
function fetchReviewSummary() {
    fetch('fetch_reviews_summary.php') // Fetch data from the PHP file that returns review summary
        .then(response => response.json())
        .then(data => {
            // Display the summary counts in the relevant HTML elements
            document.getElementById('happyCount').textContent = data.happy;
            document.getElementById('moderateCount').textContent = data.moderate;
            document.getElementById('unhappyCount').textContent = data.unhappy;
        })
        .catch(error => console.error('Error fetching review summary:', error));
}

// Fetch Users and Populate Table
function fetchUsers() {
    fetch('fetch_users.php') // Fetch data from the `users` table
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('usersTableBody');
            tableBody.innerHTML = ''; // Clear existing rows

            if (data.length === 0) {
                tableBody.innerHTML = '<tr class="no-data"><td colspan="6">No user data available.</td></tr>';
            } else {
                data.forEach((user, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>${user.password}</td>
                        <td>${user.created_at}</td>
                        <td>
                            <button class="view-details-btn" onclick="viewDetails(${user.id})">View More</button>
                            <button class="remove-user-btn" onclick="removeUser(${user.id})">Remove</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            }
        })
        .catch(error => console.error('Error fetching users:', error));
}

// Fetch Contact Us Submissions and Populate Table
function fetchContact() {
    const loadingSpinner = document.getElementById('contactLoading');
    const tableBody = document.getElementById('contactTableBody');
    const totalCountDisplay = document.getElementById('totalContactCount');
    
    // Show loading spinner
    loadingSpinner.style.display = 'block';
    tableBody.innerHTML = ''; // Clear existing rows

    fetch('fetch_contact.php') // Fetch data from the `contact_us` table
        .then(response => response.json())
        .then(data => {
            loadingSpinner.style.display = 'none'; // Hide loading spinner

            // Display total count of contact requests
            totalCountDisplay.textContent = data.length;

            if (data.length === 0) {
                tableBody.innerHTML = '<tr class="no-data"><td colspan="6">No contact requests available.</td></tr>';
            } else {
                data.forEach((contact, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${contact.name}</td>
                        <td>${contact.email}</td>
                        <td>${contact.phone}</td>
                        <td>${contact.message}</td>
                        <td>${contact.submitted_at}</td>
                    `;
                    tableBody.appendChild(row);
                });
            }
        })
        .catch(error => {
            loadingSpinner.style.display = 'none'; // Hide loading spinner
            console.error('Error fetching contact submissions:', error);
        });
}

// Fetch reviews, users, and contact data only when needed (on section switch)
// No need to fetch reviews, users, or contact data on page load anymore
document.addEventListener('DOMContentLoaded', function () {
    // Default section to show (e.g., dashboard)
    showSection('dashboard');
});
