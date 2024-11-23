<?php
require 'databaseconnection.php';
class select
{
    function VisitorInfo($artistid) {
        // Initialize an empty array to store the visitor data
        $visitorData = array();

        // Create a new database connection
        $db = new DatabaseConnect();
        $conn = $db->getConnection();

        // Prepare the SQL query to select from the 'visitor_info' view
        $query = "SELECT * FROM visitor_info WHERE artistid = ? ";

        // Prepare the statement
        if ($stmt = $conn->prepare($query)) {
            // Bind the parameter (i.e., the artist ID)
            $stmt->bind_param('i', $artistid);

            // Execute the query
            $stmt->execute();

            // Get the result of the query
            $result = $stmt->get_result();

            // Fetch all rows and store them in the array
            while ($row = $result->fetch_assoc()) {
                $visitorData[] = $row;
            }

            // Free the result set
            $result->free();

            // Close the statement
            $stmt->close();
        } else {
            // Handle query preparation error
            echo 'Error: ' . $conn->error;
        }

        // Return the visitor data array
        return $visitorData;
    }

    function artistMiniProfiles() {
        // Create a new database connection
        $db = new DatabaseConnect();
        $conn = $db->getConnection();

        // Prepare the SQL query
        // Call the stored procedure
        $sql = "CALL GetArtistProfileCards()";
        $result = $conn->query($sql);

// Fetch the results into an array
        if ($result->num_rows > 0) {
            $artistProfiles = [];

            // Fetch the result row by row
            while ($row = $result->fetch_assoc()) {
                $artistProfiles[] = $row;
            }

            // Now call the function to display the artists
        } else {
            return "No records found.";
        }


// Free the result and close the connection
        $result->free();
        $conn->close();
        return $artistProfiles;
    }


    function artist($artistId): array {
        // Create a new database connection instance
        $db = new databaseconnect();
        $conn = $db->getConnection();

        // Prepare the stored procedure call
        $stmt = $conn->prepare("CALL selectartist(?)");

        // Bind the parameter
        $stmt->bind_param("i", $artistId);

        // Execute the stored procedure
        if (!$stmt->execute()) {
            die("Execute failed: " . $stmt->error);
        }

        // Store the results
        $result = [];

        // Get the first result set (artist profile)
        $resultProfile = $stmt->get_result();
        if ($resultProfile->num_rows > 0) {
            $result['profile'] = $resultProfile->fetch_assoc();
        } else {
            $result['profile'] = null; // No profile found
        }

        // Move to the next result set (artist address)
        $stmt->next_result();
        $resultAddressSet = $stmt->get_result();
        if ($resultAddressSet->num_rows > 0) {
            $result['address'] = $resultAddressSet->fetch_all(MYSQLI_ASSOC);
        } else {
            $result['address'] = []; // No addresses found
        }

        // Move to the next result set (main service)
        $stmt->next_result();
        $resultMainServiceSet = $stmt->get_result();
        if ($resultMainServiceSet->num_rows > 0) {
            $result['main_service'] = $resultMainServiceSet->fetch_assoc(); // Fetch the main service
        } else {
            $result['main_service'] = null; // No main service found
        }

        // Move to the next result set (other services)
        $stmt->next_result();
        $resultServicesSet = $stmt->get_result();
        if ($resultServicesSet->num_rows > 0) {
            $result['services'] = $resultServicesSet->fetch_all(MYSQLI_ASSOC); // Fetch the other services
        } else {
            $result['services'] = []; // No services found
        }

        // Move to the last result set (artist media)
        $stmt->next_result();
        $resultMediaSet = $stmt->get_result();
        if ($resultMediaSet->num_rows > 0) {
            $result['media'] = $resultMediaSet->fetch_all(MYSQLI_ASSOC);
        } else {
            $result['media'] = []; // No media found
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();

        return $result;
    }

    public function customerDetails($customerId):array|string {
        $db = new databaseconnect();
        $conn = $db->getConnection();

        // Prepare the stored procedure call
        $stmt = $conn->prepare("CALL getCustomerDetails(?)");
        $stmt->bind_param("i", $customerId); // Bind customer ID as an integer

        // Execute the procedure
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            // Fetch all results
            $data = [
                'customerview' => [],
                'customeraddress' => []
            ];
            while ($row = $result->fetch_assoc()) {
                $data['customerview'][] = $row;
            }
            if ($stmt->more_results() && $stmt->next_result()) {
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $data['customeraddress'][] = $row;
                }
            }

            // Close the statement
            $stmt->close();
            $conn->close();

            // Return the JSON encoded data
            error_reporting(E_ALL); // This should be used only during development

            return $data; // Ensure this is JSON
        } else {
            error_log("Execution error: " . $stmt->error); // Log the error
            return 'Error executing procedure'; // Ensure this is also JSON
        }
    }





}


// Get the raw POST data
$input = file_get_contents('php://input');
$request = json_decode($input, true);

if (isset($request['method']) && isset($request['id'])) {
    $method = $request['method'];
    $id = $request['id'];

    $select=new select();

    // Switch or if-else to determine which function to call
    switch ($method) {
        case 'ArtistMiniProfile':
            $result = $select->artistMiniProfiles(); // Call your function here
            break;

            case 'ArtistProfile':
            $result = $select->artist($id); // Call your function here
            break;
            case 'customer':
            $result = $select->customerDetails($id); // Call your function here
            break;


        // Add more cases for other methods/functions as needed
        default:
            $result = ['error' => 'Invalid method specified.'];
            break;
    }

    // Send the result back as JSON
    header('Content-Type: application/json');
    echo json_encode($result);
}

