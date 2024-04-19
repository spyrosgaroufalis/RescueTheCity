<?php
// Include your security measures if necessary
include "security_rescuer.php";

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch data from the counter table where counter_num >= 1
$query = "SELECT * FROM counter WHERE counter_num >= 1";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

// Array to store the fetched data
$data = array();

// Fetch data for each row
while ($row = mysqli_fetch_assoc($result)) {
    $rescuerName = $row['rescuer'];

    // Query to select data from rescuer_vehicle table for the rescuer
    $queryRescuerVehicle = "SELECT * FROM rescuer_vehicle WHERE name LIKE '%$rescuerName%'";
    $resultRescuerVehicle = mysqli_query($conn, $queryRescuerVehicle);

    if (!$resultRescuerVehicle) {
        die("Database query failed: " . mysqli_error($conn));
    }

    // Fetch and store data from rescuer_vehicle table
    while ($rowRescuerVehicle = mysqli_fetch_assoc($resultRescuerVehicle)) {
        $data[] = $rowRescuerVehicle;
    }
}

// Close the database connection
mysqli_close($conn);

// Sending the data as JSON response
header('Content-Type: application/json');
echo json_encode($data);
?>
