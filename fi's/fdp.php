<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
require '../PHP/databaseconnection.php';

// Function to send the email
function sendEmail($email, $code) {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPSecure = 'ssl';
    $mail->SMTPAuth = true;
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->Username = 'niamraf12@gmail.com';
    $mail->Password = 'ckfy xmrk pnnc ptuh';
    $mail->setFrom('niamraf12@gmail.com', 'Chromatie');
    $mail->addAddress($email);
    $mail->Subject = 'Password Reset';
    $mail->Body = "Your Password Reset code is $code";

    // Send the message and check for errors
    if (!$mail->send()) {
        return "ERROR: " . $mail->ErrorInfo;
    } else {
        return "SUCCESS";
    }
}

// Function to check if email exists in the database
function checkEmail($email) {
    $db = new databaseconnect();
    $conn = $db->getConnection();

    // Prepare the SQL statement to call the stored procedure
    $stmt = $conn->prepare("CALL CheckUserExistsByEmail(?)");

    // Check if preparation is successful
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind the input parameter (email)
    $stmt->bind_param("s", $email);

    // Execute the stored procedure
    $stmt->execute();

    // Get the result from the procedure
    $result = $stmt->get_result();

    // Fetch the result as an associative array
    if ($row = $result->fetch_assoc()) {
        $found = $row['found'];

        if ($found === 'true') {
            $stmt->close();
            $conn->close();
            return true;  // Email is found (registered)
        } else {
            $stmt->close();
            $conn->close();
            return false; // Email is not found (not registered)
        }
    } else {
        $stmt->close();
        $conn->close();
        return false; // No result returned
    }
}


// Set header to return JSON content type
header('Content-Type: application/json');

// Get the JSON input data
$postData = file_get_contents("php://input");
$request = json_decode($postData, true);

// Ensure email and code are provided in the request
if (isset($request['email']) && isset($request['code'])) {
    $email = $request['email'];
    $code = $request['code'];

    // Check if the email exists
    if (checkEmail($email)) {
        // If email exists, send the email
        $result = sendEmail($email, $code);
        echo json_encode(['message' => $result]);
        exit;// Send result of sendEmail function
    } else {
        // If email doesn't exist, return an error
        echo json_encode(['message' => 'Email not registered.']);
        exit;
    }
} else {
    // Handle invalid request (missing email or code)
//    echo json_encode(['message' => 'Invalid request. Email and code are required.']);
    exit;
}
