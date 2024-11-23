document.addEventListener('DOMContentLoaded', function () {
    // Function to validate the form
    function validateForm() {
        const form = document.getElementById('custForm');
        const fName = form.fName.value.trim();
        const lName = form.lName.value.trim();
        const email = form.email.value.trim();
        const passW = form.passW.value.trim();
        const confirmPassW = form.confirmPassW.value.trim();
        const DOB = form.DOB.value;
        const gender = form.gender.value;
        
        // Simple email format validation
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Validate each field
        if (fName === '' || lName === '' || email === '' || passW === '' || confirmPassW === '' || DOB === '' || gender === '') {
            alert('All fields are required.');
            return false;
        }

        if (!emailPattern.test(email)) {
            alert('Please enter a valid email address.');
            return false;
        }

        if (passW.length < 4 || passW.length > 12) {
            alert('Password must be between 4 and 12 characters.');
            return false;
        }

        if (passW !== confirmPassW) {
            alert('Passwords do not match.');
            return false;
        }

        return true; // All validations passed
    }

    // Function to handle form submission
    function handleFormSubmission() {
        const form = document.getElementById('custForm');

        // Next button event (validate before submission)
        document.getElementById('nextBtn').addEventListener('click', (event) => {
            event.preventDefault(); // Prevent form submission
            if (validateForm()) {
                // Log form data if validation is successful
                alert('Form Submitted:');
                console.log('First Name:', form.fName.value);
                console.log('Last Name:', form.lName.value);
                console.log('Email:', form.email.value);
                console.log('Password:', form.passW.value);
                console.log('Date of Birth:', form.DOB.value);
                console.log('Gender:', form.gender.value);
            }
        });
    }

    // Function to check form completion and enable/disable the Next button
    function checkFormCompletion() {
        const form = document.getElementById('custForm');
        const nextBtn = document.getElementById('nextBtn');

        // Event listener to check if form is complete
        form.addEventListener('input', () => {
            const fName = form.fName.value.trim();
            const lName = form.lName.value.trim();
            const email = form.email.value.trim();
            const passW = form.passW.value.trim();
            const confirmPassW = form.confirmPassW.value.trim();
            const DOB = form.DOB.value;
            const gender = form.gender.value;

            // If all fields are filled and passwords match, enable the Next button
            if (fName && lName && email && passW && confirmPassW && DOB && gender && passW === confirmPassW) {
                nextBtn.disabled = false;
            } else {
                nextBtn.disabled = true; // Disable if any field is incomplete or passwords don't match
            }
        });
    }

    // Function to log user input in the form (optional, for debugging)
    function logUserInput() {
        const form = document.getElementById('custForm');
        
        // Listen to input events on the customer form
        form.addEventListener('input', (event) => {
            console.log(`Field ${event.target.name}:`, event.target.value);
        });

        // Cancel button event
        document.getElementById('cancelBtn').addEventListener('click', () => {
            console.log('Cancel button clicked');
            // Clear the form fields
            form.reset();
            document.getElementById('nextBtn').disabled = true; // Disable Next button after reset
        });
    }

    // Initialize the functions
    checkFormCompletion();
    logUserInput();
});

