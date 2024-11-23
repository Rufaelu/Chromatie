<?php
require '../PHP/select.php';
require '../PHP/insert.php';

if (isset($_GET['artistId']) &&isset($_GET['userId'])&&isset($_GET['type']) ) {
    $artistId = intval($_GET['artistId']);// Cast the value to an integer
    $userId = intval($_GET['userId']);
    $visitortype = $_GET['type'];

    $insert=new insert();
    $insert->visit($userId,$artistId,  $visitortype);

} else {
    // Handle the case when the artist ID is missing or invalid
die('no id');}




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

//
//function populateProjects()
//{
//    // Check if the $completedProjects array contains data
//    if (!empty($projects) && is_array($projects)) {
//        // Start the completed projects div
//
//
//        // Loop through the completed projects
//        foreach ($projects as $project) {
//            $projectName = htmlspecialchars($project['project_names']); // Project name
//            $projectStatus = htmlspecialchars($project['project_statuses']); // Project status
//            $role = htmlspecialchars($project['artistrole']); // Artist's role in the project
//
//            // Output each project within the div
//            echo '<div class="project-item">';
//            echo '<h4>' . $projectName . '</h4>';
//            echo '<p>Status: ' . $projectStatus . '</p>';
//            echo '<p>Role: ' . $role . '</p>';
//            echo '</div>'; // End the project item div
//        }
//
//
//    } else {
//        // If no completed projects, show a message
//        echo '<p style="background-color: transparent">No completed projects available.</p>';
//    }
//}
//

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
        echo '<div class="media-scroll-container" style=" margin-left: 1.2vw;">';

        // Create separate divs for images and videos
        echo '<div class="media-images-container" style="">';
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

        echo '<div class="media-videos-container" style=" ">';
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

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="artistdashboard.css">
    <link rel="stylesheet" href="../Resources/css/all.min.css">

    <title><?php echo $artistDetails['profile']['FullName'] ?> Profile</title>
</head>
<body>
<header class="header" style="background-color: antiquewhite ">
    <a href="login.html">    <h2 style="text-align: center" id="chrom">CHROMATIE</h2>
    </a>

    <nav>
        <ul>
            <!--        <li><a href="index.html">Home</a></li>-->
            <li id="navlinks"><a href="#">About</a> <a href="#">Contact</a></li>
        </ul>
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
        <h4 id="skill" style="text-align: center"><?php echo $mainService['Service Type'] ?></h4>

        <div id="services" style="width:auto;height:fit-content">
            <span style=" margin-left: 2vw; background-color: transparent">Services:</span>
           <?php
           if (!empty($services) && is_array($services)) {


               // Iterate over the services array
               foreach (array_slice($services, 1) as $service) {
                   // Assuming $service contains the service type, we print it inside the h4 tag
                   // Adjust based on the actual structure of the service data
                   echo '<h4 class="servicelist" style="background-color: transparent; margin-left: 2vw;">' . htmlspecialchars($service['Service Type']) . '</h4>';
               }


           } else {
               // In case no services are found, echo an empty div or some message
               echo '<div id="services" style="margin-top: 5vh;">No services available.</div>';
           }?>
        </div>

    </aside>
    <main>
        <div id="bio" class="hidden" style="height: fit-content">
            <h4 style="margin-top: 1vh; margin-left: 1vw; background-color: transparent">Bio</h4>
            <p style=" margin-left: 2vw; padding-bottom: 1vh; background-color: transparent"> <?php echo $artistDetails['profile']['bio'] ?></p>
        </div>
        <div id="services" class="hidden" style="padding-top: 1vh;height: fit-content; padding-left: 1vw;">
            <h4 style="margin-top: 1vh; margin-left: 1vw; background-color: transparent; overflow-x: hidden;">Media</h4>

            <?php echoMedia($media);?>
        </div>
        <div class="contact-container">
            <p class="contact-text">Contact Me On</p>
            <?php populateContactList(@$address);?>
        </div>
    </main>


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



    <div id="messagePopup" class="popup">

        <div class="popup-content">
            <span id="closePopupmessage" class="close-btn">&times;</span>
            <h2>Messaging</h2>
            <div class="message received">
                <div class="message-info">
                    
                    <div class="timestamp">12:07 AM</div>
                </div>
                <div class="message-content">Hey, this new Facebook Messenger is pretty cool!</div>
            </div>
            <div class="message sent">
                <div class="message-info">
                    
                    <div class="timestamp">12:11 AM</div>
                </div>
                <div class="message-content">Yeah, sure is!</div>
            </div>
            <!-- Add more messages as needed -->
        </div>
        <div class="message-input">
         
                <input type="text" placeholder="Message to send">
          
            <button>Send</button>
        </div>
    </div>
  




</section>
</body>
<script >
    document.getElementById("openPopupBtn").addEventListener("click", function() {
        
        document.getElementById("messagePopup").style.display = "block";
    }); 
    document.getElementById("startproject").addEventListener("click", function() {

    //implement sending request to the artist to start a project
        document.getElementById("popupForm").style.display = "block";

        document.getElementById("openPopupBtn").style.display = "inline";
        this.style.display="none";
    });

    document.getElementsByClassName("close-btn")[0].addEventListener("click", function() {

        document.getElementById("popupForm").style.display= 'none';


    });    document.getElementsByClassName("close-btn")[1].addEventListener("click", function() {

        document.getElementById("messagePopup").style.display= 'none';


    });
    const form = document.querySelector("form");
    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent page refresh

        // Get form data
        const projectName = document.getElementById("projectName").value;
        const projectDescription = document.getElementById("projectDescription").value;

        // You can now process the form data (e.g., send it via AJAX, display a message, etc.)
        console.log("Project Name:", projectName);
        console.log("Project Description:", projectDescription);

        // Optionally, close the popup after submission
        document.getElementById("popupForm").style.display = "none";

        // Clear form fields
        form.reset();
    });

    // window.onclick = function(event) {
    //     if (event.target === document.getElementById("messagePopup")) {
    //         document.getElementById("messagePopup").style.display = "none";
    //     }
    // };

    const hiddenelements=document.querySelectorAll(".hidden");
    const observer=new IntersectionObserver((entries)=>{
        entries.forEach((entry)=>{
            if(entry.isIntersecting){
                entry.target.classList.add('.show');
            }
            else{
                entry.target.classList.remove('.hide');
            }
        })
    })
    hiddenelements.forEach(element=>observer.observe(element));


</script>
</html>

