<?php
require "databaseconnection.php";
session_start();

class Check
{
    public function password($email, $password=null):array|string
    {
        $db = new databaseconnect();
        $conn = $db->getConnection();

    

        // Prepare the stored procedure call
        $stmt = $conn->prepare("CALL check_password(?, ?)");
        $stmt->bind_param("ss", $email, $password);  // Bind email and password as binary

        // Execute the procedure and get the result
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                if ($row['account_type'] != 'invalid') {
                    return [
                        'account_type' => $row['account_type'],  // 'artist' or 'customer'
                        'account_id' => $row['account_id']       // artist/customer ID
                    ];
                } else {
                    return ['error' => 'Invalid email or password'];
                }
            }
        }

        // Handle query failure
        return ['error' => 'Error checking credentials'];
    }
}

// Read the input from the request
$input = file_get_contents('php://input');
$request = json_decode($input, true);

// Ensure both email and password are provided in the request
if (isset($request['email']) && isset($request['password'])) {
    $email = $request['email'];
    $password = $request['password'];  // Expecting this to be an array from JS

    // Call the class function to check the password
    $check = new Check();
    $response = $check->password($email, $password);

    // Return the response as JSON
    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Invalid request. Email or password missing.']);
}
