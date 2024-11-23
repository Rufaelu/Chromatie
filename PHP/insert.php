<?php
require_once "databaseconnection.php";
class insert{
    function Artist($artistData) {
        $db = new DatabaseConnect();  // Assuming you have a DatabaseConnect class
        $conn = $db->getConnection();


        // Prepare the stored procedure call
        $stmt = $conn->prepare("CALL insert_artist(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @artist_id)");
        $artistData['media_type']='image';
        // Bind the array data to the procedure's parameters
        $stmt->bind_param(
            'sssssiissssssssss',
            $artistData['firstname'],
            $artistData['lastname'],
            $artistData['dob'],
            $artistData['gender'],
            $artistData['password'],
            $artistData['experience'],
            $artistData['phonenumber'],
            $artistData['email'],
            $artistData['instagram'],
            $artistData['telegram'],
            $artistData['whatsapp'],
            $artistData['snapchat'],
            $artistData['bio'],
            $artistData['servicecategory'],
            $artistData['servicetype'],
            $artistData['media_type'],
            $artistData['media_data']
        );
    echo $artistData['media_data'];
        // Execute the statement
        if (!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return 'Error: ' . $stmt->error;

        } else {

            // Retrieve the inserted artist ID from the output variable
            $result = $conn->query("SELECT @artist_id as artist_id");
            $row = $result->fetch_assoc();
            $artist_id = $row['artist_id'];
            $stmt->close();
            $conn->close();
            return  $artist_id;
        }

        // Close the statement and connection


    }

    function visit($visitorid, $artistid, $visitortype) {
        // Create a new database connection
        $db = new DatabaseConnect();  // Assuming you have a DatabaseConnect class
        $conn = $db->getConnection();

        // Prepare the stored procedure call
        $query = "CALL add_visit(?, ?, ?)";

        // Initialize the prepared statement
        if ($stmt = $conn->prepare($query)) {
            // Bind the input parameters
            $stmt->bind_param("iis", $visitorid, $artistid, $visitortype);  // 'i' for INT and 's' for VARCHAR

            // Execute the stored procedure
            if ($stmt->execute()) {
                echo "Visit added successfully!";
            } else {
                echo "Error executing procedure: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            // Handle the error in preparing the query
            echo "Error preparing statement: " . $conn->error;
        }

        // Close the connection
        $conn->close();
    }


    function intocustomer($customerObj): ?int
    {
        // Create a database connection
        $db = new databaseconnect();
        $conn = $db->getConnection();

        // Prepare the SQL statement to call the stored procedure (without @customerID)
        $stmt = $conn->prepare("
        CALL add_customer(
            ?, ?, ?, ?, ?, ?, ?, ?, ?
        )
    ");

        // Check if the statement was prepared successfully
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind parameters from the object
        $stmt->bind_param(
            'sssssssss',  // Specifies the types of parameters being bound
            $customerObj['firstname'],
            $customerObj['lastname'],
            $customerObj['dob'],
            $customerObj['gender'],
            $customerObj['password'],   // Assuming it's hashed binary data
            $customerObj['bio'],
            $customerObj['picture'],    // Assuming it's binary image data
            $customerObj['phone'],
            $customerObj['email']
        );

        // Execute the procedure
        if (!$stmt->execute()) {
            echo("Execute failed: " . $stmt->error);
            return null; // Return null on failure
        } else {
            echo 'Inserted successfully!';
        }

        // Close the statement
        $stmt->close();

        // Fetch the customer ID from the session variable (OUT parameter)
        $result = $conn->query("SELECT @customerID as customerID");
        if ($result) {
            $row = $result->fetch_assoc();
            $customerID = $row['customerID'];
            echo $customerID;
        } else {
            echo "Failed to retrieve customer ID.";
            return null; // Return null on failure to get customer ID
        }

        // Close the connection
        $conn->close();

        // Return the customer ID
        return $customerID;
    }



}





