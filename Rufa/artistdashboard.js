
    // Popup functionality
    const popup = document.getElementById('editasidePopup');
    const editBtn = document.getElementById('editaside');
    const closeBtn1 = document.querySelector('.close');
    const closeBtn2 = document.querySelector('#closesocial');
    const closeBtn3 = document.querySelector('#closemedia');
    const editButton = document.getElementById('editservices');
    const popupservice = document.getElementById('editServicesPopup');

    const closeButton = document.querySelector('#closeservice');
    editButton.addEventListener('click', function() {
    // Show the popup
    popupservice.style.display = 'block';

    // Get all the existing services in the services div
    const serviceElements = document.querySelectorAll('#services .servicelist');
    const additionalServicesDiv = document.getElementById('additionalServices');

    // Clear out any existing inputs
    additionalServicesDiv.innerHTML = '';

    // Loop through each service and create an input field for it
    serviceElements.forEach(serviceElement => {
    const serviceValue = serviceElement.textContent.trim(); // Get service text

    // Create new input field for each service
    const newServiceLabel = document.createElement('label');
    const newServiceInput = document.createElement('input');
    newServiceInput.setAttribute('type', 'text');
    newServiceInput.setAttribute('name', 'additionalService[]');
    newServiceInput.setAttribute('value', serviceValue); // Pre-fill with service value

    newServiceLabel.textContent = 'Service: ';
    newServiceLabel.appendChild(newServiceInput);

    // Append the new label and input to the additional services div
    additionalServicesDiv.appendChild(newServiceLabel);
    additionalServicesDiv.appendChild(document.createElement('br')); // Add line break for spacing
});
});

    // Close the popup when the user clicks on the close button
    closeButton.addEventListener('click', function() {
    popupservice.style.display = 'none';
});

    // Close the popup if the user clicks anywhere outside of the popup content
    window.addEventListener('click', function(event) {
    if (event.target === popupservice) {
    popupservice.style.display = 'none';
}
});

    editBtn.onclick = () => popup.style.display = 'block';
    closeBtn1.onclick = () => {popup.style.display = 'none';  };

    window.onclick = (e) => { if (e.target === popup) popup.style.display = 'none';   document.getElementById('mediaUploadPopup').style.display = 'none'; }


    var popupForm = document.getElementById('socialMediaPopup');
    var openPopupButton = document.querySelector('#open-popup-button');
    // Get the button that opens the modal
    openPopupButton.onclick = function() {
    popupForm.style.display = 'block';
}
    closeBtn2.onclick = () => popupForm.style.display = 'none';

    // JavaScript to dynamically add media fields
    // JavaScript to dynamically add media fields
    document.addEventListener('DOMContentLoaded', function () {
    const maxMediaCount = 4;
    let mediaCount = 1;

    const mediaUploadFields = document.getElementById('mediaUploadFields');
    const addMediaBtn = document.getElementById('addMediaBtn');
    const editMediaBtn = document.getElementById('editmediabtn');
    const mediaPopup = document.getElementById('mediaUploadPopup');
    const closeMediaBtn = document.getElementById('closemedia');

    // Show media popup when "Edit" button is clicked
    editMediaBtn.onclick = function () {
    mediaPopup.style.display = 'block';
};

    // Close media popup when close button is clicked
    closeMediaBtn.onclick = function () {
    mediaPopup.style.display = 'none';
};

    // Close media popup when clicking outside the popup content
    window.onclick = function (e) {
    if (e.target === mediaPopup) {
    mediaPopup.style.display = 'none';
}
};

    // Event listener for adding more media fields
    addMediaBtn.addEventListener('click', function () {
    if (mediaCount < maxMediaCount) {
    mediaCount++;

    // Create a new media upload input group
    const newMediaField = document.createElement('div');
    newMediaField.innerHTML = `
                <label>Media Type ${mediaCount}:
                    <select name="mediaType[]">
                        <option value="image">Image</option>
                        <option value="video">Video</option>
                        <option value="audio">Audio</option>
                    </select>
                    <input type="file" name="mediaFiles[]" accept="image/*,video/*,audio/*">
                </label>
            `;

    // Append the new media input to the mediaUploadFields div
    mediaUploadFields.appendChild(newMediaField);
}

    // Disable the add button if max count is reached
    if (mediaCount >= maxMediaCount) {
    addMediaBtn.disabled = true;
}
});
});

    // Add Another Service functionality
    document.getElementById('addServiceBtn').addEventListener('click', function() {
    const additionalServicesDiv = document.getElementById('additionalServices');

    // Create a new label and input for the new service
    const newServiceLabel = document.createElement('label');
    const newServiceInput = document.createElement('input');
    newServiceInput.setAttribute('type', 'text');
    newServiceInput.setAttribute('name', 'additionalService[]');

    newServiceLabel.textContent = 'Service: ';
    newServiceLabel.appendChild(newServiceInput);

    // Append the new label and input to the additional services div
    additionalServicesDiv.appendChild(newServiceLabel);
    additionalServicesDiv.appendChild(document.createElement('br'));
});


    document.getElementById('editabio').addEventListener('click',
    function openBioPopup() {
    document.getElementById("bioPopup").style.display = "block";
});

    // Close the bio popup
    document.getElementById("closebio").onclick = function() {
    document.getElementById("bioPopup").style.display = "none";
}

    // To close the popup if the user clicks outside the form
    window.onclick = function(event) {
    if (event.target === document.getElementById("bioPopup")) {
    document.getElementById("bioPopup").style.display = "none";
}
};




    const closeBtn = document.querySelector('.popup .close');

    // Get the data from the aside elements
    const profilePicSrc = document.getElementById('profilepic').getAttribute('src');
    const fullName = document.getElementById('name').textContent.trim();
    const services = document.querySelectorAll('.servicelist');

    // Split the full name into first name and last name
    const nameParts = fullName.split(' ');
    const firstName = nameParts[0];
    const lastName = nameParts.length > 1 ? nameParts.slice(1).join(' ') : '';

    // Show the popup and populate the form when the edit button is clicked
    editBtn.addEventListener('click', function() {
    popup.style.display = 'block';

    // Populate the form fields
    document.querySelector('input[name="firstName"]').value = firstName;
    document.querySelector('input[name="lastName"]').value = lastName;
    document.querySelector('input[name="profilePic"]').setAttribute('src', profilePicSrc);

    // Clear any existing additional services
    const additionalServicesDiv = document.getElementById('additionalServices');


    // Add each service to the form
    services.forEach(function(service) {
    const serviceLabel = document.createElement('label');
    serviceLabel.textContent = 'Service: ';
    const serviceInput = document.createElement('input');
    serviceInput.type = 'text';
    serviceInput.name = 'additionalService[]';
    serviceInput.value = service.textContent.trim();
    serviceLabel.appendChild(serviceInput);
    additionalServicesDiv.appendChild(serviceLabel);
});
});

    // Close the popup when the close button is clicked
    closeBtn.addEventListener('click', function() {
    popup.style.display = 'none';
});

    // Close the popup if the user clicks outside of the popup
    window.addEventListener('click', function(event) {
    if (event.target === popup) {
    popup.style.display = 'none';
}
});

    // Get modal elements
    const modal = document.getElementById('media-modal');
    const modalImage = document.getElementById('modal-image');
    const modalVideo = document.getElementById('modal-video');
    const deleteBtn = document.getElementById('delete-media-btn');

    // Current media ID to track what to delete
    let currentMediaId = null;

    // Close modal elements
    const closeBtnm = document.querySelector('#closemodal');

    // Function to open the modal and display the media
    function openMediaModal(mediaSrc, mediaType, mediaId) {
        modal.style.display = 'block';
        currentMediaId = mediaId; // Store the media ID for deletion

        // Set the media source based on type
        if (mediaType === 'image') {
            modalImage.src = mediaSrc;
            modalImage.style.display = 'block';
            modalVideo.style.display = 'none';
        } else if (mediaType === 'video') {
            modalVideo.src = mediaSrc;
            modalVideo.style.display = 'block';
            modalImage.style.display = 'none';
        }

        // Show the delete button
        deleteBtn.style.display = 'block';
    }

    // Event listener to close the modal
    closeBtnm.onclick = function() {
        closeModal();
    }

    // Close modal when clicking outside the content
    window.onclick = function(event) {
        if (event.target === modal) {
            closeModal();
        }
    }

    // Function to close the modal
    function closeModal() {
        modal.style.display = 'none';
        modalImage.src = '';
        modalVideo.src = '';
        deleteBtn.style.display = 'none';
    }

    // Delete media when the delete button is clicked
    deleteBtn.onclick = function() {
        // Confirm deletion
        const confirmDelete = confirm("Are you sure you want to delete this media?");
        if (confirmDelete) {
            // Send a DELETE request to the server
            deleteMedia(currentMediaId);
        }
    }

    // Function to handle media deletion
    function deleteMedia(mediaId) {
        fetch('../PHP/delete.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ mediaId: mediaId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Remove the media item from the gallery
                    document.querySelector(`.media-item-container img[id="${mediaId}"], .media-item-container video[id="${mediaId}"]`).parentElement.remove();
                    closeModal(); // Close the modal
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Attach click event listeners to media items
    document.querySelectorAll('.media-item-container').forEach(item => {
        item.addEventListener('click', function() {
            const mediaSrc = this.querySelector('img, video').src;
            const mediaType = this.querySelector('img') ? 'image' : 'video';
            const mediaId = this.querySelector('img, video').id;
            if (mediaType === 'video') {
                this.play(); // Play video inline
                return;
            }
            openMediaModal(mediaSrc, mediaType, mediaId);
        });
    });


    function deleteartist(artistId) {
        if (confirm('Are you sure you want to delete your account?')) {
            fetch('../PHP/delete.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ artistId: artistId, method: 'artist' }) // Send the artistId and the method
            })
                .then(response => response.json()) // Parse the response as JSON
                .then(data => {
                    console.log('Response data:', data); // Debug: Log the response

                    // Check if the deletion was successful
                    if (data.success) {
                    } else {
                        alert('Error: ' + data.message); // Show error message if deletion failed
                    }
                })
                .catch(error => {
                    console.error('Error deleting account:', error); // Log the error
                    // alert('An error occurred while deleting the Account.'); // Notify the user about the error
                    window.location.href = '../Bersi/login.html'; // Redirect to login page

                });
        }
    }
