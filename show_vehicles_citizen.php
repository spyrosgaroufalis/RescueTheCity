<?php
// Include your security measures if necessary
include "security_cityzen.php";

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize the $data array
$data = array();

// Query to select data from the rescuer_vehicle table
$queryRescuerVehicle = "SELECT * FROM rescuer_vehicle";
$resultRescuerVehicle = mysqli_query($conn, $queryRescuerVehicle);

// Check if the query executed successfully
if (!$resultRescuerVehicle) {
    die("Database query failed: " . mysqli_error($conn));
}

// Fetch and store data from the rescuer_vehicle table
while ($rowRescuerVehicle = mysqli_fetch_assoc($resultRescuerVehicle)) {
    $data[] = $rowRescuerVehicle;
}

// Close the database connection
mysqli_close($conn);

// Sending the data as JSON response
header('Content-Type: application/json');
echo json_encode($data);
?>
