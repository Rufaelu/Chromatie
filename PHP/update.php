<?php
require_once "databaseconnection.php";

class update
{
    function artist($data): void
    {
        // Extract the artist ID and other details from the data object
        $artistId = $data->artistid;
        $firstname = $data->firstname ?? null;
        $lastname = $data->lastname ?? null;
        $dob = $data->dob ?? null;
        $experiance = $data->experiance ?? null;
        $password = $data->password ?? null;
        $bio = $data->bio ?? null;
        $picture = $data->picture ?? null;
        $coverpicture = $data->coverpicture ?? null;

        // Database connection
        $db = new databaseconnect();
        $conn = $db->getConnection();

        // Prepare the stored procedure call
        $stmt = $conn->prepare("CALL updateartist(?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Check if the statement was prepared successfully
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind parameters (i = integer, s = string, b = binary data)
        $stmt->bind_param(
            'ississbbb',
            $artistId,
            $firstname,
            $lastname,
            $dob,
            $experiance,
            $password,
            $bio,
            $picture,
            $coverpicture
        );

        // Execute the stored procedure
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }

    function Customer($customerObj)
    {
        // Create a database connection
        $db = new databaseconnect();
        $conn = $db->getConnection();

        // Prepare the SQL statement to call the stored procedure
        $stmt = $conn->prepare("
        CALL update_customer(?, ?, ?, ?, ?, ?, ?, ?, ?)");


        // Check if the statement was prepared successfully
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }


        // Bind parameters from the customer object (use object syntax ->)
        $stmt->bind_param(
            'issssssss',  // Specifies the types of parameters being bound
            $customerObj->customerid,   // Integer
            $customerObj->firstname,    // String
            $customerObj->lastname,     // String
            $customerObj->dob,          // Date (YYYY-MM-DD format)
            $customerObj->gender,       // Single character
            $customerObj->bio,          // Text
            $customerObj->picture,      // Binary data for picture (BLOB)
            $customerObj->phone,        // String for phone
            $customerObj->email         // String for email
        );

        // Execute the procedure
        if (!$stmt->execute()) {
            echo "Execute failed: " . $stmt->error;
            return false; // Return false on failure
        } else {
            echo "Customer updated successfully!";
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();

        return true;  // Return true on success
    }

    function artistAddress($data): void
    {
        // Extract the artist ID and contact details from the data object
        $artistId = $data['artistid'];
        $phone = $data['phone'] ?? null;
        $email = $data['email'] ?? null;
        $whatsapp = $data['whatsapp'] ?? null;
        $telegram = $data['telegram'] ?? null;
        $instagram = $data['instagram'] ?? null;
        $snapchat = $data['snapchat'] ?? null;

        // Database connection
        $db = new databaseconnect();
        $conn = $db->getConnection();

        // Prepare the stored procedure call
        $stmt = $conn->prepare("CALL updateartistaddress(?, ?, ?, ?, ?, ?, ?)");

        // Check if the statement was prepared successfully
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind parameters (i = integer, s = string)
        $stmt->bind_param('issssss', $artistId, $phone, $email, $whatsapp, $telegram, $instagram, $snapchat);

        // Execute the stored procedure
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }

    function artistMedia($data, $artistId)
    {
        // Extract the data from the provided object
        $mediaTypes = $data->mediatype;  // Array of media types (like 'image', 'video', etc.)
        $mediaFiles = $data->mediadata;  // Array of media binary data or file paths

        // Database connection
        $db = new databaseconnect();
        $conn = $db->getConnection();


        // Loop through each media file and insert it into the database
        for ($i = 0; $i < count($mediaFiles); $i++) {
            $mediaType = $mediaTypes[$i];
            $mediaData = $mediaFiles[$i]; // This should now be a file path
            echo($mediaData);
            // Prepare the stored procedure call
            $stmt = $conn->prepare("CALL insertmedia(?, ?, ?)");

            // Check if the statement was prepared successfully
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }

            // Bind parameters (i = integer, s = string)
            $stmt->bind_param('iss', $artistId, $mediaType, $mediaData);

            // Execute the stored procedure
            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }

            // Close the statement
            $stmt->close();
            // Close the connection
            $conn->close();
        }
        return 'Success';

    }
    function services($artistId, $mainService, $mainServiceCategory, $additionalServices = []) {
        // Database connection
        $db = new databaseconnect();
        $conn = $db->getConnection();

        // Update main service
        $stmt = $conn->prepare("CALL UpdateArtistService(?, ?, ?)");
        if (!$stmt) {
            echo "Prepare failed: " . $conn->error;
            return false;
        }

        // Bind parameters for the main service
        $stmt->bind_param('iss', $artistId, $mainService, $mainServiceCategory);

        // Execute the procedure for the main service
        if (!$stmt->execute()) {
            echo "Error updating main service: " . $stmt->error;
            return false;
        }

        // Close the statement for the main service
        $stmt->close();

        // Update each additional service
        foreach ($additionalServices as $additionalService) {
            $stmt = $conn->prepare("CALL UpdateArtistService(?, ?, ?)");
            if (!$stmt) {
                echo "Prepare failed: " . $conn->error;
                return false;
            }

            $additionalServiceCategory = 'Other'; // Set a default category or retrieve from form

            // Bind parameters for each additional service
            $stmt->bind_param('iss', $artistId, $additionalService, $additionalServiceCategory);

            // Execute the procedure for each additional service
            if (!$stmt->execute()) {
                echo "Error updating additional service: " . $stmt->error;
                return false;
            }

            // Close the statement after each additional service
            $stmt->close();
        }

        echo "Services updated successfully!";
        return true;
    }



}