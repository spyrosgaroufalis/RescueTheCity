<?php

// Retrieve JSON data sent from the frontend
$data = json_decode(file_get_contents("php://input"), true);

// Check if 'rescuer_name' parameter is provided in the request body
if (isset($data['rescuer_name'])) {
    // Extract the 'rescuer_name' parameter
    $rescuerName = $data['rescuer_name'];

    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "help_city1");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to check if counter_num is >= 4 for the given rescuer
    $sqlCounter = "SELECT counter_num FROM counter WHERE rescuer = ?";
    $stmtCounter = $conn->prepare($sqlCounter);
    $stmtCounter->bind_param('s', $rescuerName);
    $stmtCounter->execute();
    $resultCounter = $stmtCounter->get_result();

    if ($resultCounter->num_rows > 0) {
        // Fetch the counter_num
        $rowCounter = $resultCounter->fetch_assoc();
        $counterNum = $rowCounter['counter_num'];

        // Check if counter_num is >= 4
        if ($counterNum >= 4) {
            // Update the rescuer_vehicle table
            $sqlUpdateVehicle = "UPDATE rescuer_vehicle SET status = 'Not Available' WHERE name LIKE ?";
            $stmtUpdateVehicle = $conn->prepare($sqlUpdateVehicle);
            $stmtUpdateVehicle->bind_param('s', $rescuerName);
            $stmtUpdateVehicle->execute();

            // Check if the update was successful
            if ($stmtUpdateVehicle->affected_rows > 0) {
                echo "Rescuer vehicle status updated to 'Not Available' for $rescuerName";
            } else {
                echo "Error updating rescuer vehicle status";
            }
        } else {
            // Update the rescuer_vehicle table
            $sqlUpdateVehicle = "UPDATE rescuer_vehicle SET status = 'Available' WHERE name LIKE ?";
            $stmtUpdateVehicle = $conn->prepare($sqlUpdateVehicle);
            $stmtUpdateVehicle->bind_param('s', $rescuerName);
            $stmtUpdateVehicle->execute();

            // Check if the update was successful
            if ($stmtUpdateVehicle->affected_rows > 0) {
                echo "Rescuer vehicle status updated to 'Available' for $rescuerName";
            } else {
                echo "Error updating rescuer vehicle status";
            }
        }
    } else {
        echo "No counter record found for $rescuerName";
    }

    // Close the statements and database connection
    $stmtCounter->close();
    $conn->close();
} else {
    // If 'rescuer_name' parameter is missing, send an error response
    echo "Error: rescuer_name parameter is missing";
}

?>
