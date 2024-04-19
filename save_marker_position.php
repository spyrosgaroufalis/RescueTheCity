<?php

// Retrieve JSON data sent from the frontend
$data = json_decode(file_get_contents("php://input"), true);

// Extract latitude and longitude from the data
$latitude = $data['latitude'];
$longitude = $data['longitude'];

session_start();
$username = $_SESSION['username'];

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute SQL statement to update base marker position in rescuer_vehicle table
$sqlRescuerVehicle = "UPDATE rescuer_vehicle SET latitude = ?, longitude = ? WHERE name = ?";
$stmtRescuerVehicle = $conn->prepare($sqlRescuerVehicle);
$stmtRescuerVehicle->bind_param('dds', $latitude, $longitude, $username);

if ($stmtRescuerVehicle->execute()) {
    // Success message for rescuer_vehicle table
    echo "Vehicle marker position updated successfully";
} else {
    // Error message for rescuer_vehicle table
    echo "Error updating vehicle marker position: " . $conn->error;
}

$stmtRescuerVehicle->close();

// Prepare and execute SQL statement to update polyline_start_lat and polyline_start_lon in polylines table
$sqlPolylines = "UPDATE polylines_inprogress SET polyline_start_lat = ?, polyline_start_lon = ? WHERE rescuer = ?";
$stmtPolylines = $conn->prepare($sqlPolylines);
$stmtPolylines->bind_param('dds', $latitude, $longitude, $username);

if ($stmtPolylines->execute()) {
    // Success message for polylines table
    echo "Polylines updated successfully";
} else {
    // Error message for polylines table
    echo "Error updating polylines: " . $conn->error;
}

$stmtPolylines->close();

// Prepare and execute SQL statement to update polyline_start_lat and polyline_start_lon in polylines table
$sqlPolylines2 = "UPDATE polylines SET polyline_start_lat = ?, polyline_start_lon = ? WHERE rescuer = ?";
$stmtPolylines2 = $conn->prepare($sqlPolylines2);
$stmtPolylines2->bind_param('dds', $latitude, $longitude, $username);

if ($stmtPolylines2->execute()) {
    // Success message for polylines table
    echo "Polylines updated successfully";
} else {
    // Error message for polylines table
    echo "Error updating polylines: " . $conn->error;
}

$stmtPolylines2->close();

$conn->close();

?>
