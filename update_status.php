<?php

// Retrieve JSON data sent from the frontend
$data = json_decode(file_get_contents("php://input"), true);

// Check if 'id' parameter is provided in the request body
if (isset($data['id'])) {
    // Extract the 'id' parameter
    $id = $data['id'];

    // Log the received id
    error_log("Received id: " . $id);

    // Get the current date and time
    $dateOfResponse = date('Y-m-d H:i:s');

    // Retrieve the rescuer's name from the frontend (assuming it's sent along with the 'id')
    $rescuerName = $data['rescuer_name'];

    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "help_city1");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check the counter_num value for the rescuer
    $sqlCounterNum = "SELECT counter_num FROM counter WHERE rescuer = ?";
    $stmtCounterNum = $conn->prepare($sqlCounterNum);
    $stmtCounterNum->bind_param('s', $rescuerName);
    $stmtCounterNum->execute();
    $resultCounterNum = $stmtCounterNum->get_result();

    // Check if a row exists for the counter
    if ($resultCounterNum->num_rows > 0) {
        // Fetch the counter_num value
        $rowCounterNum = $resultCounterNum->fetch_assoc();
        $counterNum = $rowCounterNum['counter_num'];

        // Check if counter_num is greater than or equal to 4
        if ($counterNum >= 4) {
            // Return an error message
            echo "Error: Counter number is already 4 or greater.";
            $stmtCounterNum->close();
            $conn->close();
            return;
        }
    }

    // Update the 'accepted' and 'date_of_response' fields in the 'announcements_cityzen' table
    $sql = "UPDATE announcements_cityzen SET accepted = 'waiting', date_of_response = ?, rescuer_name = ? WHERE id = ?";

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameters
    $stmt->bind_param('ssi', $dateOfResponse, $rescuerName, $id); // 'ssi' stands for string, string, integer type
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        // Update the counter table
        if ($resultCounterNum->num_rows > 0) {
            // If a record exists, update counter_num by incrementing it by 1
            $sqlUpdateCounter = "UPDATE counter SET counter_num = counter_num + 1 WHERE rescuer = ?";
            $stmtUpdateCounter = $conn->prepare($sqlUpdateCounter);
            $stmtUpdateCounter->bind_param('s', $rescuerName);
            $stmtUpdateCounter->execute();
        } else {
            // If no record exists, insert a new record with counter_num initialized to 1
            $sqlInsertCounter = "INSERT INTO counter (counter_num, rescuer, id) VALUES (1, ?, ?)";
            $stmtInsertCounter = $conn->prepare($sqlInsertCounter);
            $stmtInsertCounter->bind_param('si', $rescuerName, $id);
            $stmtInsertCounter->execute();
        }

        // Return success message
        echo "Status updated successfully";
    } else {
        // Return error message
        echo "Error updating status";
    }
    // Close the statement
    $stmt->close();


    // Fetch latitude and longitude from the help_offering table based on the id
    $sqlHelpOfferingCoordinates = "SELECT lat_cit, lon_cit FROM announcements_cityzen WHERE id = ?";
    $stmtHelpOfferingCoordinates = $conn->prepare($sqlHelpOfferingCoordinates);
    $stmtHelpOfferingCoordinates->bind_param('i', $id);
    $stmtHelpOfferingCoordinates->execute();
    $resultHelpOfferingCoordinates = $stmtHelpOfferingCoordinates->get_result();
  
    // Check if help_offering record exists
  if ($resultHelpOfferingCoordinates->num_rows > 0) {
    // Fetch the row
    $rowHelpOfferingCoordinates = $resultHelpOfferingCoordinates->fetch_assoc();
    // Extract latitude and longitude
    $polyline_end_lat = $rowHelpOfferingCoordinates['lat_cit'];
    $polyline_end_lon = $rowHelpOfferingCoordinates['lon_cit'];
    //$id_help_offering = $rowHelpOfferingCoordinates['id']; // Added line to fetch the id from help_offering
    $stmtHelpOfferingCoordinates->close();

    // Fetch the rescuer's vehicle latitude and longitude from the rescuer_vehicle table
    $sqlRescuerVehicleCoordinates = "SELECT latitude, longitude FROM rescuer_vehicle WHERE name = ?";
    $stmtRescuerVehicleCoordinates = $conn->prepare($sqlRescuerVehicleCoordinates);
    $stmtRescuerVehicleCoordinates->bind_param('s', $rescuerName);
    $stmtRescuerVehicleCoordinates->execute();
    $resultRescuerVehicleCoordinates = $stmtRescuerVehicleCoordinates->get_result();

    // Check if rescuer's vehicle record exists
    if ($resultRescuerVehicleCoordinates->num_rows > 0) {
        // Fetch the row
        $rowRescuerVehicleCoordinates = $resultRescuerVehicleCoordinates->fetch_assoc();
        // Extract latitude and longitude
        $polyline_start_lat = $rowRescuerVehicleCoordinates['latitude'];
        $polyline_start_lon = $rowRescuerVehicleCoordinates['longitude'];
        $stmtRescuerVehicleCoordinates->close();

        // Insert polyline_start_lat, polyline_start_lon, polyline_end_lat, and polyline_end_lon into the polylines table
        $sqlInsertPolylines = "INSERT INTO polylines_inprogress (id, rescuer, polyline_start_lat, polyline_start_lon, polyline_end_lat, polyline_end_lon) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtInsertPolylines = $conn->prepare($sqlInsertPolylines);
        $stmtInsertPolylines->bind_param('isdddd', $id, $rescuerName, $polyline_start_lat, $polyline_start_lon, $polyline_end_lat, $polyline_end_lon); // Added id_help_offering
        $stmtInsertPolylines->execute();

            // Check if the insertion was successful for polylines table
            if ($stmtInsertPolylines->affected_rows <= 0) {
                // Return error message if the insertion fails
                echo "Error inserting into polylines table";
                $stmtInsertPolylines->close();
                $conn->close();
                return;
            }

            // Close the statement for polylines table
            $stmtInsertPolylines->close();
            
            // Return success message
            echo "Status updated successfully";
        } else {
            // Return error message if rescuer's vehicle record does not exist
            echo "Error: No rescuer's vehicle found with the specified name";
        }
    } else {
        // Return error message if help_offering record does not exist
        echo "Error: No help_offering found with the specified id";
    }

    // Close the result sets and database connection
    $resultHelpOfferingCoordinates->close();


    // Close the database connection
    $conn->close();
} else {
    // If 'id' parameter is missing, send an error response
    echo "Error: ID parameter is missing";
}

?>
