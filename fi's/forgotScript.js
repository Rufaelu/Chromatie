const emailInput = document.getElementById('email-input');
const sendCodeBtn = document.getElementById('send-code-btn');
const backToSigninBtn = document.getElementById('back-to-signin-btn');
const signupBtn = document.getElementById('signup-btn');

sendCodeBtn.addEventListener('click', sendCode);
backToSigninBtn.addEventListener('click', backToSignin);
signupBtn.addEventListener('click', signupAction);

function sendCode() {
    let code = Math.floor(1000 + Math.random() * 9000);
    const email = document.getElementById("email-input").value; // Assuming email input has an id "emailInput"

    // Store code and email locally
    localStorage.removeItem("verify-code");
    localStorage.setItem("verify-code", code);
    localStorage.setItem("email", email);


        // Prepare the request body
        const jsonData = JSON.stringify({ email: email, code: code });

        // Send the request to the server using fetch
        fetch("fdp.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json", // Use JSON content type
            },
            body: jsonData,  // Send the email and code in the request body
        })
            .then(response => {
                // Attempt to parse the JSON response from PHP
                return response.json();
            })
            .then(data => {
                // Handle the response from PHP
                console.log(data.message);  // Display the success/error message

                // Check if the response message is "SUCCESS"
                if (data.message === "SUCCESS") {
                    console.log("Redirecting to verification.html");
                    window.location.href = "../fi's/verification.html"; // Redirect to verification page
                } else if (data.message === "Email not registered.") {
                    alert("The email you entered is not registered.");  // Alert if email is not found
                } else {
                    alert("An error occurred: " + data.message);  // Handle other error cases
                }
            })
            .catch(error => {
                // Handle fetch request errors
                console.error("Error sending request: ", error);
                alert("Error sending request: " + error.message);  // Display error to the user
            });



}


function backToSignin() {
 window.location.href = '../Bersi/login.html'; // Replace with your actual signin page URL
}

function signupAction() {

 window.location.href = '../Nessi/HTML/Registration Form.html'; // Replace with your actual signup page URL

}


 // Simple email validation (you can use a more robust validation)
 function validateEmail() {
    window.location.href="verification.html"

 }
