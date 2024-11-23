


  const editButton = document.querySelector('.edit-button');
  let isEditing = false;
  const profileImage = document.getElementById('profile-image');
  const imageUploadInput = document.getElementById('profile-image-upload');
  const profileName = document.getElementById('profile-name');
const email=document.getElementById('email')
const phone=document.getElementById('phone')
const gender=document.getElementById('gender')
  editButton.addEventListener('click', () => {
    const profileFields = document.querySelectorAll('.profile-about p span');
    const bioField = document.querySelector('.profile-bio p span');
    const formData = new FormData();
    formData.append('customerid',localStorage.getItem('userid'));

    if (isEditing) {
      if(email.innerText===''){
        alert('Please provide email')

      } if(phone.innerText===''){
        alert('Please provide a Phone Number')

      } if (gender.innerText.toLowerCase() === '' || (gender.innerText.toLowerCase() !== 'f' && gender.innerText.toLowerCase() !== 'm')) {
        alert("Please Enter a Valid Gender (F or M)");
      }


      // Save changes
      profileFields.forEach((field, index) => {
        const input = field.nextElementSibling; // Get the input field next to the span
        if (input && input.tagName === 'INPUT') {
          field.textContent = input.value; // Set the span text to the input value
          input.remove(); // Remove the input field
          field.style.display = 'inline'; // Show the span again
        }
      });

      const bioTextarea = bioField.nextElementSibling; // Get the textarea next to the span
      if (bioTextarea && bioTextarea.tagName === 'TEXTAREA') {
        bioField.textContent = bioTextarea.value; // Set the span text to the textarea value
        bioTextarea.remove(); // Remove the textarea
        bioField.style.display = 'inline'; // Show the span again
      }

      // Save the profile name
      const nameInput = profileName.nextElementSibling; // Get the input field next to the name
      if (nameInput && nameInput.tagName === 'INPUT') {
        profileName.textContent = nameInput.value; // Set the name text to the input value
        nameInput.remove(); // Remove the input field
        profileName.style.display = 'inline'; // Show the h2 element again
      }

      // Save the uploaded profile image if any
      if (imageUploadInput.files.length > 0) {
        const file = imageUploadInput.files[0];
        formData.append('profileImage', file); // Add the image file to the FormData
        profileImage.src = URL.createObjectURL(file);      }
      senddata(formData);

      imageUploadInput.style.display = 'none';  // Hide the file input after saving
      editButton.textContent = 'Edit';  // Change button text back to 'Edit'
    } else {
      // Make fields editable
      profileFields.forEach(field => {
        const input = document.createElement('input');
        input.value = field.textContent;  // Set input value to current text
        field.style.display = 'none';  // Hide the span
        field.parentNode.insertBefore(input, field.nextSibling);  // Add the input after the span
      });

      const bioSpan = bioField;
      if (bioSpan) {
        const textarea = document.createElement('textarea');
        textarea.value = bioSpan.textContent;  // Set textarea value to current text
        textarea.rows = 5;  // Adjust the rows of the textarea
        textarea.style.width = '100%';  // Set the textarea width
        bioField.style.display = 'none';  // Hide the span
        bioField.parentNode.insertBefore(textarea, bioField.nextSibling);  // Add the textarea after the span
      }

      // Enable profile name editing
      const nameInput = document.createElement('input');
      nameInput.value = profileName.textContent; // Set input value to current name
      profileName.style.display = 'none';  // Hide the name
      profileName.parentNode.insertBefore(nameInput, profileName.nextSibling);  // Add input after the name

      // Show the profile image upload (browse) button
      imageUploadInput.style.display = 'inline';  // Show the file input to upload an image

      editButton.textContent = 'Save';  // Change button text to 'Save'
    }

    isEditing = !isEditing;  // Toggle editing mode
  });

// Event listener to trigger file input click when profile image is clicked
  profileImage.addEventListener('click', () => {
    if (isEditing) {
      imageUploadInput.click();  // Open the file upload dialog
    }
  });

  function senddata(formData){
    formData.append('email',email.innerText);
    formData.append('phone',phone.innerText);
    formData.append('gender',gender.innerText);
    let name=splitFullName(profileName.innerText);
    formData.append('firstname',name.firstName);
    formData.append('lastname',name.lastName);
    formData.append('bio',document.getElementById('bio').innerText);
    formData.append('dob',document.getElementById('dob').innerText);
    // formData.;
    // Send all profile data to the server
    fetch('updatecustomer.php', {
      method: 'POST',
      body: formData
    })
        .then(response => response.text()) // Fetch response as text
        .then(text => {
          console.log('Raw response: ' + text); // Log raw response to inspect
          return JSON.parse(text); // Manually parse it to JSON
        })
        .then(data => {
          if (data.success) {

            alert('Profile updated successfully');
          } else {
            alert('Profile update failed');
          }
        })
        .catch(error => console.error('Error:', error));


  }

function splitFullName(fullName) {
  let nameParts = fullName.trim().split(" ");

  let firstName = nameParts[0]; // The first part will be the first name
  let lastName = nameParts.length > 1 ? nameParts.slice(1).join(" ") : ""; // Everything else will be the last name

  return { firstName, lastName };
}
function deletecustomer(customerId) {
  if (confirm('Are you sure you want to delete your account?')) {
    fetch('../../PHP/delete.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ customerId: customerId, method: 'customer' }) // Send the artistId and the method
    })
        .then(response => response.json()) // Parse the response as JSON
        .then(data => {
          console.log('Response data:', data); // Debug: Log the response

          // Check if the deletion was successful
          if (data.success) {
            alert('Account deleted successfully: ' + data.message); // Notify the user
            window.location.href = '../Bersi/login.html'; // Redirect to login page
          } else {
            alert('Error: ' + data.message); // Show error message if deletion failed
          }
        })
        .catch(error => {
          console.error('Error deleting account:', error); // Log the error
          // alert('An error occurred while deleting the Account.'); // Notify the user about the error
          window.location.href = '../../Bersi/login.html'; // Redirect to login page

        });
  }
}

