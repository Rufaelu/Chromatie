<?php
require_once "../../PHP/update.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json'); // Add correct header

    // Initialize customer object
    $customerObj = new stdClass();
    $update = new update();

    // Collect form data
    $customerObj->customerid = $_POST['customerid'];
    $customerObj->firstname = $_POST['firstname'];
    $customerObj->lastname = $_POST['lastname'];
    $customerObj->dob = $_POST['dob'];
    $customerObj->gender = $_POST['gender'];
    $customerObj->bio = $_POST['bio'];
    $customerObj->phone = $_POST['phone'];
    $customerObj->email = $_POST['email'];

    $imagePath = null;  // Initialize image path as null

    // Handle profile image upload
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profileImage'];
        $targetDir = '../../uploads/media/';  // Define the folder to save the image
        $targetFile = $targetDir . basename($file['name']);  // Define the target file path

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;  // Set image path to return
            $customerObj->picture = '../uploads/media/'. basename($file['name']);  // Store the path in the customer object
        } else {
            echo json_encode(['success' => false, 'error' => 'Image upload failed']);
            exit;  // Ensure the script stops if an error occurs
        }
    }

    // Call the function to update customer information
    if ($update->Customer($customerObj)) {
        // Only return the image path if a new image was uploaded
        echo json_encode(['success' => true, 'imagePath' => $imagePath ? $imagePath : null]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update customer']);
    }
}
