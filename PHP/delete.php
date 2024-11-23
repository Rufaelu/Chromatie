<?php
require "databaseconnection.php";

class delete
{
    function customer($customerId): string
    {

        // Database connection
        $db = new databaseconnect();
        $conn = $db->getConnection();

        // Prepare the stored procedure call
        $stmt = $conn->prepare("CALL delete_customer(?)");

        // Check if the statement was prepared successfully
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind the customer ID parameter (i = integer)
        $stmt->bind_param('i', $customerId);

        // Execute the stored procedure
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
        return json_encode(['success' => true, 'message' => "Artist successfully deleted."]);

    }

    function artist($artistId): string
    {
        // Database connection
        $db = new databaseconnect();
        $conn = $db->getConnection();

        // Prepare the stored procedure call
        $stmt = $conn->prepare("CALL DeleteArtist(?)");

        // Check if the statement was prepared successfully
        if (!$stmt) {
            return json_encode(['success' => false, 'message' => "Prepare failed: " . $conn->error]);
        }

        // Bind the artist ID parameter (i = integer)
        $stmt->bind_param('i', $artistId);

        // Execute the stored procedure
        if (!$stmt->execute()) {
            return json_encode(['success' => false, 'message' => "Execute failed: " . $stmt->error]);
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
        return json_encode(['success' => true, 'message' => "Artist successfully deleted."]);
    }
    function artistAddress($data): void
    {
        // Extract the artist ID and boolean values from the data object
        $artistId = $data->artistid;
        $phone = $data->phone ? 1 : 0;
        $email = $data->email ? 1 : 0;
        $whatsapp = $data->whatsapp ? 1 : 0;
        $telegram = $data->telegram ? 1 : 0;
        $instagram = $data->instagram ? 1 : 0;
        $snapchat = $data->snapchat ? 1 : 0;

        // Database connection
        $db = new databaseconnect();
        $conn = $db->getConnection();

        // Prepare the stored procedure call
        $stmt = $conn->prepare("CALL deleteartistaddress(?, ?, ?, ?, ?, ?, ?)");

        // Check if the statement was prepared successfully
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind parameters (i = integer, b = boolean/integer)
        $stmt->bind_param('iiiiiii', $artistId, $phone, $email, $whatsapp, $telegram, $instagram, $snapchat);

        // Execute the stored procedure
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }

    function artistMedia($mediaId) {
        // Database connection
        $db = new databaseconnect();
        $conn = $db->getConnection();

        // Prepare the stored procedure call
        $stmt = $conn->prepare("CALL deleteartistmedia(?)");

        // Check if the statement was prepared successfully
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind the mediaId parameter (i = integer)
        $stmt->bind_param('i', $mediaId);

        // Execute the stored procedure
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        // Fetch the result message from the procedure
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $message = $row['message'];

        // Close the statement and connection
        $stmt->close();
        $conn->close();

        // Return the result message
        return $message;
    }
    function service( $artistId, $serviceId) {
        $db = new databaseconnect();
        $conn = $db->getConnection();
        // Prepare the SQL statement to call the stored procedure
        $stmt = $conn->prepare("CALL DeleteArtistService(?, ?)");

        // Bind the parameters
        $stmt->bind_param("ii", $artistId, $serviceId);

        // Execute the statement
        if ($stmt->execute()) {
            // Check if any rows were affected (deleted)
            if ($stmt->affected_rows > 0) {
                $stmt->close();

                return "Service successfully deleted.";
            } else {
                $stmt->close();

                return "No service found for the specified artist.";
            }
        } else {
            $error = $stmt->error;
            // Handle errors
            $stmt->close();

            return "Error: " . $error;
        }

        // Close the statement
    }

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON input
    $data = json_decode(file_get_contents('php://input'));

    // Check for artistId and serviceId in the POST request
    if (isset($data->artistId) && isset($data->serviceId)) {
        // Handle artist service deletion
        $artistId = intval($data->artistId); // Convert to integer for safety
        $serviceId = intval($data->serviceId); // Convert to integer for safety

        // Create the delete object and call the artistService method
        $delete = new Delete(); // Assuming you have a Delete class
        $message = $delete->service($artistId, $serviceId); // Change this to your deletion method

        // Return the result message as JSON
        echo json_encode(['success' => true, 'message' => $message]);
    } elseif (isset($data->mediaId)) {
        // Handle media deletion
        $mediaId = intval($data->mediaId); // Convert to integer for safety

        // Create the delete object and call the artistMedia method
        $delete = new Delete(); // Assuming you have a Delete class
        $message = $delete->artistMedia($mediaId); // Change this to your deletion method

        // Return the result message as JSON
        echo json_encode(['success' => true, 'message' => $message]);
    } elseif (isset($data->method) && $data->method === "artist") {
        $artistId = intval($data->artistId); // Convert to integer for safety
        echo 'About to Delete';

        $delete = new Delete();
        $message = $delete->artist($artistId);
        header('Content-Type: application/json'); // Ensure the response is JSON

        echo $message; // Return the message from the delete operation
    }elseif (isset($data->method) && $data->method === "customer") {
        $customerId = intval($data->customerId); // Convert to integer for safety
        echo 'About to Delete';

        $delete = new Delete();
        $message = $delete->customer($customerId);
        header('Content-Type: application/json'); // Ensure the response is JSON

        echo $message; // Return the message from the delete operation
    }
    else {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

