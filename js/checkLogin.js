// checkLogin.js
function checkSignIn(event, url) {
    event.preventDefault(); // Prevent the default action of the button
    
    // Make the request to check login status
    fetch('checkLogin.php')
        .then(response => response.json()) // Parse JSON response
        .then(data => {
            console.log('Response from checkLogin.php:', data); // Debug: log the response
            
            if (!data.loggedIn) {
                // User not logged in, show alert without redirecting
                alert("You need to sign in or sign up to access this section.");
            } else {
                // User is logged in, proceed to the category page
                window.location.href = url;
            }
        })
        .catch(error => {
            console.error('Error checking login status:', error); // Handle any fetch errors
        });
  }
  