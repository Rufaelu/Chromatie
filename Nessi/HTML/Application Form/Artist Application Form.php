<?php
ob_start(); // Start output buffering
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Application Form</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@40,400,0,0" />
    <link rel="stylesheet" href="../../CSS/Application Form/AAF.css">
</head>
<body>
    <header id="header">
        <h1>Artist Application Form</h1>
        <span class="material-symbols-outlined">arrow_back</span>
    </header>

    <div class="container">
        <div class="info_section">
            <form id="artistForm" name="artistForm" method="POST" enctype="multipart/form-data">
                <div class="name-section">
                    <div class="input-group">
                        <label for="fName" class="label">First Name</label>
                        <input type="text" name="fName" required id="fName" placeholder="Enter First Name">
                    </div>
                    <div class="input-group">
                        <label for="lName" class="label">Last Name</label>
                        <input type="text" name="lName" id="lName" placeholder="Enter Last Name">
                    </div>
                </div>

                <label for="email" class="label">Email</label>
                <input type="email" name="email" required id="email" placeholder="Enter Email">

                <label for="passW" class="label">Password</label>
                <input type="password" name="passW" required id="passW" placeholder="Enter password">
                <p class="info_text">Your password should be between 4 and 12 characters</p>

                <label for="confirmPassW" class="label">Confirm Password</label>
                <input type="password" name="confirmPassW" required id="confirmPassW" placeholder="Enter password">
                <span id="passwordMatchMessage" style="display: none"></span>
                <label for="DOB" class="label">Date of Birth</label>
                <input type="date" name="DOB" id="DOB" required placeholder="M/D/Y">

                <label for="gender" class="label">Gender</label>
                <select id="gender"  name="gender" required>
                    <option value="">Gender:</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>

                <label for="skillName" class="label">Service</label>
                <input type="text" name="skillName" required id="skillName" placeholder="Enter service">

                <label for="skill" class="label">Service Category</label>
                <select id="skill"  name="skill" required>
                    <option value="">Services:</option>
                    <option value="Visual Arts">Visual Arts</option>
                    <option value="Performing Arts">Performing Arts</option>
                    <option value="Literary Arts">Literary Arts</option>
                    <option value="Digital Arts">Digital Arts</option>
                    <option value="Other">Other</option>

                </select><br><br>
                <div class="sub_section" style="width: 93%">
                    <h2>Drag and Drop Or Browse </h2>
                   
                        <label for="UploadDragFile" class="DragFilelbl">Upload Profile Picture</label><br>
                        <div id="DragFile"  class="DragFile">Drag Files</div><br>
                        <input type="file" name="fileInput"  id="fileInput" accept="image/*" style="display:none;" required>
                        <p class="info_text">Attach file. File size of your documents should not exceed 10MB</p>
                       
                    
                </div>

                <button id="cancelBtn" type="button">Cancel</button>
                <input type="submit" class="submitbtn" value="Submit">
<!--                <button id="nextBtn" type="button" disabled>Next</button>-->
            </form>
        </div>

<!--        <div class="sub_section">-->
<!--            <h2>Drag and Drop File Uploading</h2>-->
<!--            <form id="fileUploadForm" name="fileUploadForm">-->
<!--                <label for="UploadDragFile" class="DragFilelbl">Upload Additional file</label><br>-->
<!--                <div id="DragFile" class="DragFile">Drag Files</div><br>-->
<!--                <input type="file" id="fileInput" style="display: none;" required>-->
<!--                <p class="info_text">Attach file. File size of your documents should not exceed 10MB</p>-->
<!--                <button class="submitbtn">Submit</button>-->
<!--            </form>-->
<!--        </div>-->
   </div>
    <script src="../../JS/Application Form/AAF.js" defer></script>
</body>
</html>

<?php

require_once '../../../PHP/insert.php';
$artistData =[];
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errors = [];

    // Additional validations
    if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    // Password validation
    if (!empty($_POST['passW'])) {
        $password = $_POST['passW'];
        if (strlen($password) < 4 || strlen($password) > 12) {
            $errors['passW'] = "Password must be between 4 and 12 characters.";
        }
        if ($_POST['passW'] !== $_POST['confirmPassW']) {
            $errors['confirmPassW'] = "Passwords do not match.";
        }
    }

    // Validate file upload
    if (isset($_FILES['fileInput']) && $_FILES['fileInput']['error'] === UPLOAD_ERR_OK) {
        if ($_FILES['fileInput']['size'] > 100 * 1024 * 1024) { // 100MB limit
            $errors['fileInput'] = "File size should not exceed 100MB.";
        }
    } else {
        $errors['fileInput'] = "File upload is required.";
    }

    // If no errors, process the form
    if (empty($errors)) {
        echo "Form submitted successfully!";

        // Escape and sanitize input
        $firstname = htmlspecialchars($_POST['fName']);
        $lastname = htmlspecialchars($_POST['lName']);
        $dob = htmlspecialchars($_POST['DOB']);
        $gender = htmlspecialchars($_POST['gender']);
        $password = passwordToBinaryData($_POST['passW']); // Encrypt the password
        $email = htmlspecialchars($_POST['email']);
        $servicecategory = htmlspecialchars($_POST['skill']);
        $servicetype = htmlspecialchars($_POST['skillName']); // Placeholder for service type

        $media_type = null; // Initialize media type as null
        $media_path = null; // Initialize media path as null

        if (isset($_FILES['fileInput']) && $_FILES['fileInput']['error'] === UPLOAD_ERR_OK) {
            // Define the directory to move the file
            $targetDir =  "../../../uploads/media/"; // Full path to the target directory

            // Ensure directory exists, or create it
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Get the original file name
            $fileName = basename($_FILES['fileInput']['name']);
            // Define the target file path
            $targetFilePath = $targetDir . $fileName;

            // Move the file to the target directory
            if (move_uploaded_file($_FILES['fileInput']['tmp_name'], $targetFilePath)) {
                // Successfully moved, now store the relative file path in the database
                $media_path = "../uploads/media/" . $fileName;
                $mime_type = $_FILES['fileInput']['type'];

                // Set media type based on the MIME type
                if (strpos($mime_type, 'image') !== false) {
                    $media_type = 'image';
                } elseif (strpos($mime_type, 'video') !== false) {
                    $media_type = 'video';
                } else {
                    $media_type = 'unknown'; // Fallback if it's not an image or video
                }
            } else {
                $errors['fileInput'] = "Failed to upload the file.";
            }
        }

        // Create the array of artist data
        if (empty($errors)) {
            $artistData = [
                'firstname' =>  ucfirst(strtolower($firstname)),
                'lastname' =>  ucfirst(strtolower($lastname)),
                'dob' => $dob,
                'gender' => $gender,
                'password' => $password,
                'experience' => null,
                'phonenumber' => null,
                'email' => $email,
                'instagram' => null,
                'telegram' => null,
                'whatsapp' => null,
                'snapchat' => null,
                'bio' => null,
                'servicecategory' => $servicecategory,
                'servicetype' => ucfirst(strtolower($servicetype)),
                'media_type' => $media_type,
                'media_data' => $media_path // Store the file path instead of binary data
            ];
echo $artistData['media_data'];
            $insert = new insert();
            $id = $insert->Artist($artistData);

            if (is_numeric($id)) {
                header("Location: ../../../fi's/home.php?type=artist&userId=" . urlencode($id));
               exit();
            } else {
                echo "Failed to register.";
            }
        }
    } else {
        // Display validation errors
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}



function passwordToBinaryData($password) {
    $binaryString = '';

    // Loop through each character of the password
    for ($i = 0; $i < strlen($password); $i++) {
        // Get ASCII value of each character, add 1, and convert back to a character
        $asciiValue = ord($password[$i]) + 1;
        $binaryString .= chr($asciiValue);
    }

    // Encode binary as Base64
    return base64_encode($binaryString);
}

?>
