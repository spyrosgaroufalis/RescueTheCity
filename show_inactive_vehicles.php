<?php
// Include your security measures if necessary
include "security.php";

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select names from the counter table where counter_num >= 1
$queryCounterNames = "SELECT rescuer FROM counter WHERE counter_num >= 1";
$resultCounterNames = mysqli_query($conn, $queryCounterNames);

if (!$resultCounterNames) {
    die("Database query failed: " . mysqli_error($conn));
}

// Array to store the names from the counter table
$counterNames = array();

// Fetch and store names from the counter table
while ($rowCounterNames = mysqli_fetch_assoc($resultCounterNames)) {
    $counterNames[] = $rowCounterNames['rescuer'];
}

// Query to select data from the rescuer_vehicle table excluding names from the counter table
$queryRescuerVehicle = "SELECT * FROM rescuer_vehicle WHERE name NOT IN ('" . implode("','", $counterNames) . "')";
$resultRescuerVehicle = mysqli_query($conn, $queryRescuerVehicle);

if (!$resultRescuerVehicle) {
    die("Database query failed: " . mysqli_error($conn));
}

// Array to store the fetched data
$data = array();

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
