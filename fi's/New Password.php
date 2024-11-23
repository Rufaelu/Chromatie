
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Password</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="back-arrow">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M15.41 7.41L14 6L8 12L14 18L15.41 16.59L10.83 12L15.41 7.41Z" fill="currentColor"></path>
      </svg>
    </div>
    <h1>New Password</h1>
      <form method="post" > <!-- Add the action attribute -->
          <h2>Enter New Password</h2>
          <input type="password" id="new-password" name="new-password" placeholder="At least 4 digits" required />
          <h2>Confirm Password</h2>
          <input type="password" id="confirm-password" name="confirm-password" placeholder="****" required />
          <button type="submit" id="update-password-btn">Send</button>
      </form>

  </div>
  <div class="back-arrow">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M15.41 7.41L14 6L8 12L14 18L15.41 16.59L10.83 12L15.41 7.41Z" fill="currentColor"></path>
    </svg>
  </div>

  <script src="nscript.js"></script> 
</body>
</html>

<?php
require_once '../PHP/databaseconnection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['email'])) {
        $email = $_GET['email'];
    } else {
        die(json_encode(["status" => "error", "message" => "Email not provided"]));
    }
echo $email;
    if (isset($_POST['new-password']) && isset($_POST['confirm-password'])) {
        if ($_POST['new-password'] === $_POST['confirm-password']) {
            echo $email;
            $message=updatePassword(passwordToBinaryData($_POST['new-password']), $email);
if($message=="password changed"){
            header('Location: ../Bersi/login.html');
            exit; }// Ensure no further code execution happens after the redirect
       else echo $message;
        } else {
            echo json_encode(["status" => "error", "message" => "Passwords do not match"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "New password and confirm password must be provided"]);
    }
}


function updatepassword($password, $email) {
    // Database connection
    $db = new databaseconnect();
    $conn = $db->getConnection();

    // Prepare the stored procedure call
    $stmt = $conn->prepare("CALL updatepassword(?, ?)");

    // Check if the statement was prepared successfully
    if (!$stmt) {
        $conn->close();
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters as plain strings (no encryption since it's already encrypted)
    $stmt->bind_param('ss', $email, $password);

    // Execute the stored procedure
    if (!$stmt->execute()) {
        $stmt->close();
        $conn->close();
        die("Execute failed: " . $stmt->error);
    }

    // Capture the result from the stored procedure
    $stmt->store_result();

    // Bind the result (the message returned from the procedure)
    $stmt->bind_result($resultMessage);

    // Fetch the result
    if ($stmt->fetch()) {
        // Close the statement and connection before returning
        $stmt->close();
        $conn->close();

        return $resultMessage;  // Return the message like 'password changed' or 'user not found'
    } else {
        $stmt->close();
        $conn->close();
        return "Failed to fetch result.";
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