document.addEventListener('DOMContentLoaded', () => {
    // Function to fetch user data and display it in the table
    function loadUserData() {
        fetch('fetch_users.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('#usersTable tbody');
                tableBody.innerHTML = ''; // Clear any existing data

                if (data.length === 0) {
                    // If no data, show a message
                    const row = document.createElement('tr');
                    row.innerHTML = '<td colspan="5" class="no-data">No users found</td>';
                    tableBody.appendChild(row);
                } else {
                    // Loop through each user and create table rows
                    data.forEach(user => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${user.id}</td>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td>${user.created_at}</td>
                            <td>
                                <button class="view-details" data-user-id="${user.id}">View Details</button>
                                <button class="remove-user" data-user-id="${user.id}">Remove User</button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });

                    // Attach event listeners for View Details and Remove User buttons
                    attachEventListeners();
                }
            })
            .catch(error => {
                console.error('Error fetching user data:', error);
                const tableBody = document.querySelector('#usersTable tbody');
                tableBody.innerHTML = '<tr><td colspan="5">Error fetching data</td></tr>';
            });
    }

    // Attach event listeners for buttons
    function attachEventListeners() {
        document.querySelectorAll('.view-details').forEach(button => {
            button.addEventListener('click', viewUserDetails);
        });

        document.querySelectorAll('.remove-user').forEach(button => {
            button.addEventListener('click', confirmRemoveUser);
        });
    }

    // View User Details function
    function viewUserDetails(event) {
        const userId = event.target.getAttribute('data-user-id');
        alert('View details for User ID: ' + userId);
        // You can add a detailed modal or redirect to a detailed page as needed
    }

    // Remove User function
    function confirmRemoveUser(event) {
        const userId = event.target.getAttribute('data-user-id');
        openConfirmationModal(userId);
    }

    // Open confirmation modal for user deletion
    function openConfirmationModal(userId) {
        const modal = document.getElementById('confirmationModal');
        const confirmButton = document.getElementById('confirmRemove');
        const cancelButton = document.getElementById('cancelRemove');
        
        modal.style.display = 'block'; // Show modal

        // Handle the confirm button click
        confirmButton.onclick = () => {
            removeUser(userId);
            closeConfirmationModal();
        };

        // Handle the cancel button click
        cancelButton.onclick = closeConfirmationModal;
    }

    // Remove the user from the database
    function removeUser(userId) {
        fetch('remove_user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('User removed successfully!');
                loadUserData(); // Refresh the table after deletion
            } else {
                alert('Failed to remove user.');
            }
        })
        .catch(error => {
            console.error('Error removing user:', error);
            alert('An error occurred while removing the user.');
        });
    }

    // Close the confirmation modal
    function closeConfirmationModal() {
        const modal = document.getElementById('confirmationModal');
        modal.style.display = 'none';
    }

    // Load user data when the page is loaded
    loadUserData();
});
