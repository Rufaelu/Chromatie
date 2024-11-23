document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('artistForm');
    const nextBtn = document.getElementById('nextBtn');
    
    // Function to validate form fields
    function validateForm() {
        let isValid = true;
        const requiredFields = ['fName', 'lName', 'email', 'passW', 'confirmPassW', 'DOB', 'gender', 'skillName', 'skill'];
        
        requiredFields.forEach(field => {
            const input = form[field];
            if (!input.value) {
                isValid = false;
            }
        });

        const email = form.email.value;
        if (!validateEmail(email)) {
            isValid = false;
        }

        const password = form.passW.value;
        const confirmPassword = form.confirmPassW.value;
        if (password !== confirmPassword) {
            isValid = false;
        }

        nextBtn.disabled = !isValid;
    }

    // Function to validate email format
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }

    // Attach event listeners for input validation
    form.addEventListener('input', validateForm);

    // Cancel button event
    document.getElementById('cancelBtn').addEventListener('click', () => {
        console.log('Cancel button clicked');
        form.reset();
        // validateForm(); // Reset the button state
    });

    // // Next button event (simulating form submission)
    // nextBtn.addEventListener('click', (event) => {
    //     event.preventDefault(); // Prevent form submission
    //     alert('Form Submitted:');
    //
    //     alert('First Name: '+ form.fName.value);
    //     console.log('Last Name:', form.lName.value);
    //     console.log('Email:', form.email.value);
    //     console.log('Password:', form.passW.value);
    //     console.log('Date of Birth:', form.DOB.value);
    //     console.log('Gender:', form.gender.value);
    //     console.log('Skill Name:', form.skillName.value);
    //     console.log('Skill Category:', form.skill.value);
    // });

    // File upload logic remains unchanged



});
handleFileUpload();
function handleFileUpload() {
    const dragFile = document.getElementById('DragFile');
    const fileInput = document.getElementById('fileInput');
    const maxFileSize = (10 * 1024 * 1024)*10; // 10 MB

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dragFile.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dragFile.addEventListener(eventName, () => dragFile.classList.add('highlight'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dragFile.addEventListener(eventName, () => dragFile.classList.remove('highlight'), false);
    });

    dragFile.addEventListener('drop', (event) => {
        const files = event.dataTransfer.files;
        if (files.length) {
            const file = files[0];
            if (file.size > maxFileSize) {
                alert(`Error: The file size exceeds 100MB. Your file size is ${(file.size / (1024 * 1024)).toFixed(2)}MB.`);
            } else {
                console.log(`File uploaded successfully: ${file.name}`);
                dragFile.textContent = `File uploaded: ${file.name}`;
            }
        }
    });

    dragFile.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', () => {
        const files = fileInput.files;
        if (files.length) {
            const file = files[0];
            if (file.size > maxFileSize) {
                alert(`Error: The file size exceeds 100MB. Your file size is ${(file.size / (1024 * 1024)).toFixed(2)}MB.`);
            } else {
                console.log(`File selected: ${file.name}`);
                dragFile.textContent = `File uploaded: ${file.name}`;
            }
        }
    });
}

const password = document.getElementById('passW')
let confirmPassword = document.getElementById('confirmPassW')
confirmPassword.addEventListener('input',function() {

    const message = document.getElementById('passwordMatchMessage');

    // Check if the passwords match
    if (password.value === confirmPassword.value) {
        message.style.display='block';
        message.textContent = 'Passwords match';
        message.style.color = 'green';
    } else {
        message.style.display='block';

        message.textContent = 'Passwords do not match';
        message.style.color = 'red';
    }
});