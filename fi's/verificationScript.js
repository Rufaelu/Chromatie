// Select the input fields and buttons
const codeInputs = document.querySelectorAll('.varnum');
const sendBtn = document.querySelector('.send-btn');
const resendLink = document.querySelector('a[href="#"]');
const signupBtn = document.querySelector('.signup-btn');

// Ensure input fields accept only one digit and move focus to the next field automatically
codeInputs.forEach((input, index) => {
  input.addEventListener('input', function() {
    if (this.value.length > 1) {
      this.value = this.value.slice(0, 1); // Keep only the first character
    }

    // Automatically move to the next input field
    if (this.value.length === 1 && index < codeInputs.length - 1) {
      codeInputs[index + 1].focus();
    }
  });
});

// Function to validate the verification code
function validateCode() {
  const sentCode = localStorage.getItem('verify-code'); // Get the stored code
  let enteredCode = '';

  // Concatenate the entered digits from all input fields
  codeInputs.forEach(input => {
    enteredCode += input.value;
  });

  // Ensure the entered code is 4 digits long
  if (enteredCode.length === 4) {
    // Check if the entered code matches the sent code
    if (enteredCode === sentCode) {
      // Redirect to the password reset page if the codes match
      window.location.href = 'New Password.php?email='+localStorage.getItem('email');
    } else {
      // Display an error message if the codes don't match
      alert('Incorrect code. Please try again.');
    }
  } else {
    // Display an error message if the code is not 4 digits
    alert('Please enter a 4-digit code.');
  }
}

// Function to handle resending the code
function resendCode(event) {
  // Prevent the default link action
  event.preventDefault();

  // Re-trigger the process to resend the code (you can implement the actual code-sending logic here)
  sendCode(true); // Assuming sendCode() triggers the AJAX request to resend the code
  alert('Verification code resent!');
}

// Function to handle the "Sign Up" button
function signupAction() {
  // Redirect to the sign-up page
  window.location.href = '../Nessi/HTML/Registeration Form.html';
}

// Add event listeners for the input fields to ensure only numeric values are entered
codeInputs.forEach(input => {
  input.addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, ''); // Allow only numbers
  });
});

// Event listeners for buttons
sendBtn.addEventListener('click', validateCode);
resendLink.addEventListener('click', resendCode);
signupBtn.addEventListener('click', signupAction);

// Handle the back arrow to redirect to the forgot password page
const backArrow = document.querySelector('.back-arrow');
backArrow.addEventListener('click', () => {
  window.location.href = 'forgotPass.html';
});
