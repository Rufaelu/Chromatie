<!-- <?php
require '../PHP/select.php';
require '../PHP/update.php';
ob_start(); // Start output buffering

if (isset($_GET['profileId'])) {
    $artistId = intval($_GET['profileId']);  // Cast the value to an integer

} else {
$artistId =  $_SESSION['artist_id'];
}
function getCurrentUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $requestUri = $_SERVER['REQUEST_URI'];

    return $protocol . $host . $requestUri;
}
$select=new select();
$artistDetails=$select->artist($artistId);
$profile = $artistDetails['profile'];
$address = $artistDetails['address'];

$mainService = $artistDetails['main_service'];
$services = $artistDetails['services'];
$media = $artistDetails['media'];
//$projects = $artistDetails['projects'];
$profilepicture = $artistDetails['profile']['picture']; // Assuming 'Picture' is in binary format
$coverpicture = $artistDetails['profile']['Coverpicture']??'../uploads/media/default cover.jpg'; // Assuming 'Picture' is in binary format
// Detect the image type based on the first few bytes (optional, but recommended)
function checktype($image){
$imageSignature = substr($image, 0, 8); // Check first 8 bytes for PNG signature

if (str_starts_with($imageSignature, "\xFF\xD8\xFF")) {
return 'image/jpeg';
} elseif (str_starts_with($imageSignature, "\x89\x50\x4E\x47")) {
return 'image/png';
} else {
// Default to JPEG if type can't be determined
return 'image/jpeg';
}


}

function populateProjects()
{
// Check if the $completedProjects array contains data
if (!empty($projects) && is_array($projects)) {
// Start the completed projects div


// Loop through the completed projects
foreach ($projects as $project) {
$projectName = htmlspecialchars($project['project_names']); // Project name
$projectStatus = htmlspecialchars($project['project_statuses']); // Project status
$role = htmlspecialchars($project['artistrole']); // Artist's role in the project

// Output each project within the div
echo '<div class="project-item">';
    echo '<h4>' . $projectName . '</h4>';
    echo '<p>Status: ' . $projectStatus . '</p>';
    echo '<p>Role: ' . $role . '</p>';
    echo '</div>'; // End the project item div
}


} else {
// If no completed projects, show a message
echo '<p style="background-color: transparent">No completed projects available.</p>';
}
}


function populateContactList($ad) {
    // Check if $address contains valid data
    if (!empty($ad) && is_array($ad)) {
        // Start the contact list
        echo '<ul class="contact-list">';

        // Loop through the address array to populate each list item
        foreach ($ad as $contact) {
            $type = htmlspecialchars($contact['Class']); // The updated type (e.g., 'phone', 'whatsapp', etc.)
            $link = htmlspecialchars($contact['href']); // The link to the contact (e.g., phone number, email, or URL)
            $rotate='';

            // Map the new type values back to the original icon classes
            switch ($type) {
                case 'phone':
                    $iconClass = 'fas fa-phone contact-logo';
                    $linkUrl = 'tel:' . $link;
                    $rotate='style=" transform: rotate(90deg);"';

                    break;
                case 'email':
                    $iconClass = 'fas fa-envelope contact-logo';
                    $linkUrl = 'mailto:' . $link;
                    break;
                case 'whatsapp':
                    $iconClass = 'fab fa-whatsapp contact-logo';
                    $linkUrl = 'https://wa.me/' . $link;
                    break;
                case 'instagram':
                    $iconClass = 'fab fa-instagram contact-logo';
                    $linkUrl = 'https://instagram.com/' . $link;
                    break;
                case 'snapchat':
                    $iconClass = 'fab fa-snapchat-ghost contact-logo';
                    $linkUrl = 'https://snapchat.com/add/' . $link;
                    break;
                case 'telegram':
                    $iconClass = 'fab fa-telegram-plane contact-logo';
                    $linkUrl = 'https://t.me/' . $link;
                    break;
                default:
                    // Handle unknown types with a default icon
                    $iconClass = 'fa fa-link contact-logo'; // Default icon
                    $linkUrl = htmlspecialchars($link); // Default link behavior
                    break;
            }

            // Generate the list item with the correct icon class and link
            echo '<li>
                <a href="' . $linkUrl . '" target="_blank"><i class="' . $iconClass . ' "'.$rotate.'></i></a>
            </li>';
        }

        // End the contact list
        echo '</ul>';
    } else {
        echo '<ul class="contact-list"><li>No contact information available.</li></ul>';
    }
}

function echoMedia($media) {
    if (!empty($media) && is_array($media)) {
        // Start the scrollable container
        echo '<div class="media-scroll-container">';

        // Create separate divs for images and videos
        echo '<div class="media-images-container" >';
        echo '<h3>Images</h3>';
        foreach ($media as $mediaItem) {
            $mediaType = strtolower(htmlspecialchars($mediaItem['File Type']));
            $mediaPath = htmlspecialchars($mediaItem['Path']);

            if ($mediaType == 'image') {
                // Output an image using the path from the database
                echo '<div class="media-item-container" style="background-color: transparent; " >';
                echo '<img src="' . $mediaPath . '" alt="Artist Media" id="'. htmlspecialchars($mediaItem['mediaid']) .'" class="media-item media-image">';
                echo '</div>';
            }
        }
        echo '</div>'; // End of media-images-container

        echo '<div class="media-videos-container" style="background-color: transparent">';
        echo '<h3>Videos</h3>';
        foreach ($media as $mediaItem) {
            $mediaType = strtolower(htmlspecialchars($mediaItem['File Type']));
            $mediaPath = htmlspecialchars($mediaItem['Path']);

            if ($mediaType == 'video') {
                // Output a video using the path from the database
                echo '<div class="media-item-container" style="background-color: transparent;padding-top: 3.7vh;">';
                echo '<video controls class="media-item media-video" id="'. htmlspecialchars($mediaItem['mediaid']) .'">
                    <source src="' . $mediaPath . '" type="video/mp4">
                    Your browser does not support the video tag.
                </video>';
                echo '</div>';
            }
        }
        echo '</div>'; // End of media-videos-container

        // End the scrollable container
        echo '</div>';
    } else {
        echo '<p class="no-media-message">No media available.</p>';
    }
}


?>

 -->
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="artistdashboard.css">
    <link rel="stylesheet" href="../Resources/css/all.min.css">

    <title><?php echo $artistDetails['profile']['FullName'] ?> Dashboard</title>
</head>
<body>
<header class="header" style="background-color: antiquewhite ">
    <a href="../Bersi/login.html">    <h2 style="text-align: center" id="chrom">CHROMATIE</h2>
    </a>

    <nav>
       <a href="#">About</a>
        <a href="#">Contact</a>
        <button id="deleteaccount" onclick="deleteartist(<?php echo $artistId ?>);" style="background-color: transparent; width: fit-content; ">Delete Account</button>

    </nav>
</header>

<section id="body">
    <div id="coverimage">

        <img src="<?php echo $coverpicture?>" alt="Cover Image" style="background-color: transparent">


    </div>


    <aside id="asidecontainer">
        <img src="<?php echo $profilepicture?>" alt="<?php echo $profile['FullName']?>" title="<?php echo $profile['FullName']?>" id="profilepic" style="background-color: transparent">
        <h4 id="name" style="text-align: center"> <?php  echo $profile['FullName']?></h4>
        <br>
        <h4 id="skill" serviceid="<?php echo $mainService['Service ID'] ?>"  style="text-align: center"><?php echo $mainService['Service Type'] ?></h4>
        <button id="editaside" title="edit this shit">Edit</button>
        <div id="services" style="width:14vw;height:fit-content">
            <span style=" margin-left: 2vw; background-color: transparent">Services:</span>
            <?php
           if (!empty($services) && is_array($services)) {

                if(count($services)<2)
                    echo 'There are no additional services';
                else               // Iterate over the services array
               foreach (array_slice($services, 1) as $service) {
                   // Assuming $service contains the service type, we print it inside the h4 tag
                   // Adjust based on the actual structure of the service data
                   echo '<h4 class="servicelist" serviceid="' . htmlspecialchars($service['Service ID']) . '" style="background-color: transparent; padding-left: 2vw;">'
                       . htmlspecialchars($service['Service Type']) . '</h4>';            }


            } else {
            // In case no services are found, echo an empty div or some message
            echo '<div id="services" style="margin-top: 5vh;">No services available.</div>';
            }?>
        </div>
        <button id="editservices" title="edit this shit">Edit Other Services</button>

    </aside>
    <main>
        <div id="bio" class="hidden" style="height: fit-content;  width: 100%;">
            <h4 style="margin-top: 1vh; margin-left: 1vw; background-color: transparent">Bio</h4>
            <p style=" margin-left: 2vw; margin-right: 1vw; padding-bottom: 1vh; background-color: transparent;word-wrap: break-word"> <?php echo $artistDetails['profile']['bio'] ?></p>
        </div>
        <button id="editabio">Edit</button>

        <div id="services" class="media" style="padding-top: 1vh; padding-left: 1vw; height:auto;display:flex; flex-direction: row;">
            <h4 style="margin-top: 1vh; margin-left: 1vw; background-color: transparent">Media</h4>

            <?php echoMedia($media);?>
        </div>
        <button id="editmediabtn">Edit</button>

        <div class="contact-container " >
            <p class="contact-text" id="open-popup-button">Contact Me On</p>
            <?php populateContactList(@$address);?>
        </div>
    </main>
    <aside id="projects" style="display: none;">
        <div id="completed" style="height:fit-content">
            <?php populateProjects();?>
        </div>
        <!--        <button style="width:6.3vw;" id="startproject">Start Project</button>-->
        <!--        <button id="openPopupBtn" style="width:6.3vw; display: none; ">Start Chat</button>-->
    </aside>

    <div id="popupForm" class="popup">
        <div class="popup-content">
            <span id="closePopupform" class="close-btn"  style="font-size: 7vh;">&times;</span>
            <h2>Project Details</h2>
            <form>
                <label for="projectName">Project Name:</label><br>
                <input type="text" id="projectName" name="projectName" style="width: 30vw; height: 5vh; font-size: 3vh;"><br><br>

                <label for="projectDescription">Project Description:</label><br>
                <textarea id="projectDescription" name="projectDescription" style="width: 30vw; height: 10vh;font-size: 2vh;"></textarea><br><br>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>


    <div id="media-modal" class="modal" >
        <span class="close" id="closemodal" style="color: white; margin-right: 5vw; background-color: transparent">&times;</span>
        <img class="modal-content" id="modal-image" style="display:none;" alt="image">
        <video controls class="modal-content" id="modal-video" style="display:none;"></video>

        <!-- Delete button -->
        <button id="delete-media-btn" class="delete-btn" style="display:none;">Delete Media</button>
    </div>



    <div id="editasidePopup" class="popup" style="display: none">
        <div class="popup-content">
            <span class="close">&times;</span>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="form_type" value="name">

                <label>First Name: <input type="text" name="firstName" ></label>
                <label>Last Name: <input type="text" name="lastName" ></label>
                <label>Date of Birth: <input type="date" name="dob" ></label>
                <label>Profile Picture: <input type="file" name="profilePic" accept="image/*"></label>
                <label>Cover Picture: <input type="file" name="coverPic" accept="image/*"></label>
                <br>
                <button type="submit" >Save</button>
            </form>
        </div>
    </div>

    <div id="bioPopup" class="popup" style="display: none;">
        <div class="popup-content">
            <span class="close" id="closebio">&times;</span>

            <!-- Form for updating bio -->
            <form id="bioForm" method="POST">
                <input type="hidden" name="form_type" value="bio">

                <label>Bio:
                    <textarea name="bio" placeholder="Enter your bio here..." rows="5" style="width:95%; margin-left: 1vw;" required></textarea>
                </label>

                <br>
                <button type="submit">Save</button>
            </form>
        </div>
    </div><!-- Popup Form Container -->


    <div id="socialMediaPopup" class="popup" style="display: none;">
        <div class="popup-content">
            <span class="close" id="closesocial">&times;</span>

            <!-- Form for registering social media and contact information -->
            <form id="socialMediaForm" method="POST">
                <input type="hidden" name="form_type" value="contact">

                <label>Email: <input type="email" name="email"</label>

                <label>Instagram: <input type="text" name="instagram" placeholder="@username"></label>

                <label>Telegram: <input type="text" name="telegram" placeholder="@username"></label>

                <label>Phone: <input type="tel" name="phone" placeholder="Enter phone number"></label>

                <label>WhatsApp: <input type="tel" name="whatsapp" placeholder="Enter WhatsApp number"></label>

                <label>Snapchat: <input type="text" name="snapchat" placeholder="@username"></label>

                <br>
                <button type="submit">save</button>
            </form>
        </div>
    </div><!-- Popup Form Container -->
    <div id="mediaUploadPopup" class="popup" style="display: none;">
        <div class="popup-content"> 
            <span class="close" id="closemedia">&times;</span>

            <!-- Form for uploading media -->
            <form id="mediaUploadForm" method="POST"  enctype="multipart/form-data">
                <div id="mediaUploadFields">
                    <input type="hidden" name="form_type" value="media">

                    <!-- First media type and file input -->
                    <label>Media Type 1:
                        <select name="mediaType[]">
                            <option value="image">Image</option>
                            <option value="video">Video</option>
                            <option value="audio">Audio</option>
                        </select>
                        <input type="file" name="mediaFiles[]" accept="image/*,video/*,audio/*">
                    </label>
                </div>

                <!-- Button to add more media fields -->
                <button type="button" id="addMediaBtn">+ Add Another Media</button>
                <br>

                <!-- Submit Button -->
                <button type="submit" name="uploadMediaBtn">Upload Media</button>
            </form>
        </div>
    </div>

    <!-- Popup Div -->
    <div id="editServicesPopup" class="popup" style="display: none;">
        <div class="popup-content">
            <span class="close" id="closeservice">&times;</span>

            <!-- Form Inside Popup -->
            <form id="serviceForm" method="POST" >
                <label>Main Service: <input type="text" name="mainService" id="mainServiceInput" value="<?php echo $mainService['Service Type']; ?>"></label>
                <label>Service Category:
<!--                    <input type="text" name="mainservicecategory" value="--><?php //echo $mainService['Service Category']; ?><!--" required>-->
                    <select id="skill" name="mainservicecategory" required>
                        <option value="Visual Arts" <?php if ($mainService['Service Category'] == 'Visual Arts') echo 'selected'; ?>>Visual Arts</option>
                        <option value="Performing Arts" <?php if ($mainService['Service Category'] == 'Performing Arts') echo 'selected'; ?>>Performing Arts</option>
                        <option value="Literary Arts" <?php if ($mainService['Service Category'] == 'Literary Arts') echo 'selected'; ?>>Literary Arts</option>
                        <option value="Digital Arts" <?php if ($mainService['Service Category'] == 'Digital Arts') echo 'selected'; ?>>Digital Arts</option>
                        <option value="Other" <?php if ($mainService['Service Category'] == 'Other') echo 'selected'; ?>>Other</option>
                    </select>
                </label>
                <input type="hidden" name="form_type" value="service">

                <div id="additionalServices">
                    <!-- Additional services will be inserted here dynamically -->
                </div>
                <button type="button" id="addServiceBtn">Add Another Service</button>
                <br>
                <button type="submit">Save</button>
            </form>
        </div>
    </div>




</section>
</body>
<script src="artistdashboard.js"></script>
<script>
    document.getElementById('editservices').addEventListener('click', function() {
        // Get all the existing services in the services div
        const serviceElements = document.querySelectorAll('#services .servicelist');
        const additionalServicesDiv = document.getElementById('additionalServices');

        // Main service from PHP
        document.getElementById('mainServiceInput').value = "<?php echo $mainService['Service Type']; ?>";

        // Clear any previously added services in case the form was used before
        additionalServicesDiv.innerHTML = '';

       // Assuming you have the artist ID available in a variable
        const artistId = <?php echo $artistId; ?>; // Set the artist ID from PHP

// Populate the form with additional services (skipping the main service)
        <?php if (!empty($services) && count($services) > 1) { ?>
        const additionalServices = <?php echo json_encode(array_slice($services, 1)); ?>;
        additionalServices.forEach(function(service) {
            // Create a div to hold the label and inputs together
            const serviceWrapper = document.createElement('div');
            serviceWrapper.style.display = 'inline-block'; // Ensures the elements stay on the same line
            serviceWrapper.style.marginBottom = '10px'; // Optional, for spacing between services

            // Create a label for the additional service input
            const serviceLabel = document.createElement('label');
            serviceLabel.innerText = 'Additional Service: '; // Label text
            serviceLabel.htmlFor = 'additionalService_' + service['Service ID']; // Associate label with input
            serviceLabel.style.marginRight = '10px'; // Add some space between label and input

            // Create the service input
            const serviceInput = document.createElement('input');
            serviceInput.type = 'text';
            serviceInput.name = 'additionalService[]';
            serviceInput.id = 'additionalService_' + service['Service ID']; // Set unique ID for label association
            serviceInput.value = service['Service Type']; // Set the service type value

            // Create a hidden input to store the service ID
            const serviceIdInput = document.createElement('input');
            serviceIdInput.type = 'hidden';
            serviceIdInput.name = 'serviceId[]';
            serviceIdInput.value = service['Service ID']; // Set the service ID value

            // Create a delete button
            const deleteServiceBtn = document.createElement('button');
            deleteServiceBtn.innerText = 'X';
            deleteServiceBtn.type = 'button'; // Prevent form submission
            deleteServiceBtn.style.marginLeft = '10px'; // Add space between input and button
            deleteServiceBtn.style.height = '1vh';
            deleteServiceBtn.style.backgroundColor = 'transparent';
            deleteServiceBtn.style.width = '1vw';

            // Add an event listener to handle the delete action
            deleteServiceBtn.addEventListener('click', function() {
                const serviceId = service['Service ID']; // Get the service ID
                deleteService(artistId, serviceId); // Call your delete function with artist ID and service ID

                // Remove the service wrapper, which contains the label, input, hidden input, and delete button
                additionalServicesDiv.removeChild(serviceWrapper);
            });

            // Append label, inputs, and button to the wrapper
            serviceWrapper.appendChild(serviceLabel); // Append the label
            serviceWrapper.appendChild(serviceInput); // Append the input
            serviceWrapper.appendChild(serviceIdInput); // Append the hidden input
            serviceWrapper.appendChild(deleteServiceBtn); // Append the delete button

            // Append the wrapper to the form
            additionalServicesDiv.appendChild(serviceWrapper);
        });
        <?php } ?>


        // // Loop through each existing service element (just in case you want to populate from DOM)
        // serviceElements.forEach(serviceElement => {
        //     const serviceValue = serviceElement.textContent.trim(); // Get the service text
        //     const serviceId = serviceElement.getAttribute('data-serviceid'); // Get the service ID from custom attribute
        //
        //     // Create new input field for the service
        //     const newServiceInput = document.createElement('input');
        //     newServiceInput.type = 'text';
        //     newServiceInput.name = 'additionalService[]';
        //     newServiceInput.value = serviceValue; // Pre-fill with service value
        //
        //     // Create a hidden input for the service ID
        //     const newServiceIdInput = document.createElement('input');
        //     newServiceIdInput.type = 'hidden';
        //     newServiceIdInput.name = 'serviceId[]';
        //     newServiceIdInput.value = serviceId; // Store the service ID
        //
        //     // Append the inputs to the form
        //     additionalServicesDiv.appendChild(newServiceInput);
        //     additionalServicesDiv.appendChild(newServiceIdInput);
        //     additionalServicesDiv.appendChild(document.createElement('br')); // Add line break for spacing
        // });

        // Show the popup (make sure your popup display logic is in place)
        document.getElementById('editServicesPopup').style.display = 'block';
    });

    // Close popup functionality (optional, ensure the close button is functioning)
    document.getElementById('closeservice').addEventListener('click', function() {
        document.getElementById('editServicesPopup').style.display = 'none';
    });

    function deleteService(artistId, serviceId) {
        // Here, you can send an AJAX request or call another function to handle the deletion
        console.log(`Deleting service with ID: ${serviceId} for artist ID: ${artistId}`);
        // Example of an AJAX request (uncomment and modify based on your needs):

        fetch('../PHP/delete.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ artistId: artistId, serviceId: serviceId })
        })
            .then(response => response.json())
            .then(data => {
                // Check if the deletion was successful
                if (data.success) {
                    alert('Service deleted successfully: ' + data.message); // Alert the user
                    console.log('Service deleted:', data); // Log the response for debugging
                } else {
                    alert('Error: ' + data.message); // Alert in case of an error
                }
            })
            .catch(error => {
                console.error('Error deleting service:', error); // Log the error
                alert('An error occurred while deleting the service.'); // Alert the user about the error
            });


    }

</script>
</html>


<?php



// Ensure you have a database connection class for this to work
    require_once '../PHP/databaseconnection.php';

function updateArtistProfile($artistId)
{
    // Extract form data
    $firstname = !empty($_POST['firstName']) ? $_POST['firstName'] : null;
    $lastname = !empty($_POST['lastName']) ? $_POST['lastName'] : null;
    $dob = !empty($_POST['dob']) ? $_POST['dob'] : null;
    $experience = !empty($_POST['experience']) ? $_POST['experience'] : null;
    $password = !empty($_POST['password']) ? $_POST['password'] : null;
    $bio = !empty($_POST['bio']) ? $_POST['bio'] : null;

    // Define the upload directory
    $targetDir = "../uploads/media/";// Full path to the target directory


    // Ensure the directory exists, or create it
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Handling profile picture
    $profilePicturePath = null;
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
        $profilePicName = basename($_FILES['profilePic']['name']);
        $profilePicPath = $targetDir . $profilePicName;

        // Move the profile picture to the target directory
        if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $profilePicPath)) {
            $profilePicturePath = $targetDir . $profilePicName; // Save the relative path
        } else {
            die("Error uploading profile picture.");
        }
    }

    // Handling cover picture
    $coverPicturePath = null;
    if (isset($_FILES['coverPic']) && $_FILES['coverPic']['error'] === UPLOAD_ERR_OK) {
        $coverPicName = basename($_FILES['coverPic']['name']);
        $coverPicPath = $targetDir . $coverPicName;

        // Move the cover picture to the target directory
        if (move_uploaded_file($_FILES['coverPic']['tmp_name'], $coverPicPath)) {
            $coverPicturePath = $targetDir . $coverPicName; // Save the relative path
        } else {
            die("Error uploading cover picture.");
        }
    }

    // Database connection
    $db = new databaseconnect();
    $conn = $db->getConnection();

    // Prepare the stored procedure call
    $stmt = $conn->prepare("CALL updateartist(?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind the parameters, handling potential nulls
    $stmt->bind_param(
        'isssissss',
        $artistId,
        $firstname,
        $lastname,
        $dob,
        $experience,
        $password,
        $bio,
        $profilePicturePath,
        $coverPicturePath
    );

    // Execute the stored procedure
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    } else {
        echo "Artist updated";
    }

    $stmt->close();





    // Close the connection
    $conn->close();

    // Return success response
    header("Location: artistdashboard.php?profileId=".$artistId);
    exit();
}


// Call the function when the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['form_type'])) {
            switch ($_POST['form_type']) {
                case 'media':
                    updateArtistMedia($artistId);
                    break;
                case 'name':
                    updateArtistProfile($artistId);
                    break;
                case 'contact':
                    updateArtistaddress($artistId); 
                    break;
                case 'bio':
                        updateBio($artistId);
                        break;
                case 'service':
                    updateArtisService($artistId);
                    break;

                default:
                    echo json_encode(['status' => 'error', 'message' => 'Invalid form type']);
            }                    // Add other cases as needed
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        }

    }

    function updateArtisService($artistId){
        $mainService = $_POST['mainService'];
        $additionalServices = !empty($_POST['additionalService']) ? $_POST['additionalService'] : [];

            $mainServiceCategory = $_POST['mainservicecategory'];


            // Call the function to update the services
          $update=new update();
        $result=  $update->services( $artistId, $mainService, $mainServiceCategory,  $additionalServices);
        if($result){
            echo '<script type="text/javascript">alert(" Service is updated");</script>';

            header("Location: artistdashboard.php?profileId=".$artistId);
            exit();
        }
        else {
            echo '<script type="text/javascript">alert(" Service is not updated");</script>';

        }


    }


function updateArtistaddress($artistId ){

    // Initialize an array to hold the non-null fields
    $nonNullData =[];
    $nonNullData['artistid']=$artistId ;

    // Check each field if it's not empty and add it to the array
    if (!empty($_POST['email'])) {
        $nonNullData['email'] = htmlspecialchars($_POST['email']);
    }
    if (!empty($_POST['instagram'])) {
        $nonNullData['instagram'] = htmlspecialchars($_POST['instagram']);
    }
    if (!empty($_POST['telegram'])) {
        $nonNullData['telegram'] = htmlspecialchars($_POST['telegram']);
    }
    if (!empty($_POST['phone'])) {
        $nonNullData['phone'] = htmlspecialchars($_POST['phone']);
    }
    if (!empty($_POST['whatsapp'])) {
        $nonNullData['whatsapp'] = htmlspecialchars($_POST['whatsapp']);
    }
    if (!empty($_POST['snapchat'])) {
        $nonNullData['snapchat'] = htmlspecialchars($_POST['snapchat']);
    }

    // Check if any data was submitted
    if (!empty($nonNullData)) {
        // You can now pass this data to your PHP function that processes the data
        // For example:
        $update = new Update();
        $update->artistAddress($nonNullData);
        header("Location: artistdashboard.php?profileId=".$artistId);
        exit();
        // Provide feedback to the user
       
    } else {
        echo "<p>No data submitted.</p>";
    }

}

// Function to handle updating the artist's bio
function updateBio($artistId) {
    // Check if the form is submitted and bio is set

        // Get the bio from the form
        $bio = trim($_POST['bio']);  // Use trim to remove unnecessary whitespace

        // Check if bio is not empty
        if (!empty($bio)) {
            // Call the function to update the bio in the database
            updateArtistBio($artistId, $bio);
            header("Location: artistdashboard.php?profileId=".$artistId);
            exit();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Bio cannot be empty']);
        }
    
}

// Function to update the bio in the database
function updateArtistBio($artistId, $bio) {
    // Database connection
    $db = new databaseconnect();
    $conn = $db->getConnection();

    // Prepare the stored procedure call to update the bio
    $stmt = $conn->prepare("CALL updateArtistBio(?, ?)");

    // Check if the statement was prepared successfully
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters (i = integer, s = string)
    $stmt->bind_param('is', $artistId, $bio);

    // Execute the stored procedure
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

function updateArtistMedia($artistId) {
    // Check if the form is submitted and form type is media
    if ( isset($_POST['mediaType']) && isset($_FILES['mediaFiles'])) {
        if (isset($_POST['form_type']) && $_POST['form_type'] === 'media') {

            // Process each uploaded media file

                // Define the directory to move the files
                $targetDir = "../uploads/media/";

                // Ensure directory exists, or create it
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                // Initialize arrays for media paths and types
                $mediaPaths = [];
                $mediaTypes = [];

                // Loop through each uploaded file
                foreach ($_FILES['mediaFiles']['name'] as $index => $fileName) {
                    if ($_FILES['mediaFiles']['error'][$index] === UPLOAD_ERR_OK) {
                        // Get the original file name and define the target file path
                        $fileName = basename($fileName);
                        $targetFilePath = $targetDir . $fileName;

                        // Move the file to the target directory
                        if (move_uploaded_file($_FILES['mediaFiles']['tmp_name'][$index], $targetFilePath)) {
                            // Successfully moved, store the relative file path in an array
                            $mediaPaths[] = "../uploads/media/" . $fileName;
                            $mimeType = $_FILES['mediaFiles']['type'][$index];

                            // Determine media type based on the MIME type
                            if (str_contains($mimeType, 'image')) {
                                $mediaTypes[] = 'image';
                            } elseif (str_contains($mimeType, 'video')) {
                                $mediaTypes[] = 'video';
                            } else {
                                $mediaTypes[] = 'unknown'; // Fallback if it's not an image or video
                            }
                        } else {
                            echo '<script type="text/javascript">alert("Failed to upload file: '.$fileName.'");</script>';
                        }
                    } else {
                        echo '<script type="text/javascript">alert("Error uploading file: '.$fileName.'");</script>';
                    }
                }

                // Now call the artistMedia function to save the uploaded media paths
                $data = (object)[
                    'mediatype' => $mediaTypes,
                    'mediadata' => $mediaPaths
                ];

                $update=new update();
                $update->artistMedia($data, $artistId);
            header("Location: artistdashboard.php?profileId=".$artistId);
            exit();
        }
        }

}
ob_end_flush(); // End output buffering and flush the output

