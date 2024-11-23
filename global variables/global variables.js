
let artistId = null;
let customerId = null;

function updateGlobalVars(artist = null, custom = null) {
    if (artist) {
        localStorage.setItem('artistId', artist);
    }
    if (custom) {
        localStorage.setItem('customerId', custom);
    }
}



// Data to send to the server
const data = {
    email: 'example@example.com',
    password: 'newPassword123'
};

// Prepare the request payload
const requestData = {
    method: 'updatepassword', // method name corresponding to the PHP switch case
    data: data // the data that includes the email and password
};

function passwordToBinaryData(password) {
    let binaryString = '';

    for (let i = 0; i < password.length; i++) {
        let asciiValue = password.charCodeAt(i)+1;  // Get ASCII value of each character
        binaryString += String.fromCharCode(asciiValue); // Build a string from ASCII values
    }


    // Encode binary as Base64
    return btoa(binaryString);
}


function getPendingArtistDetail(artistId) {
    const url = 'path_to_your_php_script.php'; // Replace with actual URL to your PHP file
    const data = {
        method: '1pending', // Specify the method
        id: artistId       // The artist ID
    };

    // Send the POST request using fetch
    fetch('../PHP/select.php', {
        method: 'POST',            // Define the HTTP method
        headers: {
            'Content-Type': 'application/json' // Set the content type to JSON
        },
        body: JSON.stringify(data)  // Convert data to JSON string
    })
        .then(response => {
            // Check if the response is successful (status code 200-299)
            if (!response.ok) {
                throw new Error('Network response was not ok. Status: ' + response.status);
            }
            return response.json(); // Parse JSON from the response
        })
        .then(result => {
            // Handle the result (the pending artist details)
            console.log(result); // Replace this with your own logic (like updating the DOM)
        })
        .catch(error => {
            // Handle errors (network issues, etc.)
            console.error('There was an error with the fetch operation:', error);
        });
}

function getPendingArtistsDetails() {
    const url = 'path_to_your_php_script.php'; // Replace with actual URL to your PHP file


    // Send the POST request using fetch
    fetch('../PHP/select.php', {
        method: 'POST',            // Define the HTTP method
        headers: {
            'Content-Type': 'application/json' // Set the content type to JSON
        },
        body: JSON.stringify({method:'pendings'})  // Convert data to JSON string
    })
        .then(response => {
            // Check if the response is successful (status code 200-299)
            if (!response.ok) {
                throw new Error('Network response was not ok. Status: ' + response.status);
            }
            return response.json(); // Parse JSON from the response
        })
        .then(result => {
            // Handle the result (the pending artist details)
            console.log(result); // Replace this with your own logic (like updating the DOM)
        })
        .catch(error => {
            // Handle errors (network issues, etc.)
            console.error('There was an error with the fetch operation:', error);
        });
}



// Send the fetch request
