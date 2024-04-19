<?php

// Retrieve JSON data sent from the frontend
$data = json_decode(file_get_contents("php://input"), true);

// Extract latitude and longitude from the data
$latitude = $data['latitude'];
$longitude = $data['longitude'];

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute SQL statement to update base marker position
$sql = "UPDATE base_marker_position SET latitude = $latitude, longitude = $longitude WHERE id = 1"; // Assuming the base marker has ID 1

if ($conn->query($sql) === TRUE) {
    // Success message
    echo "Base marker position updated successfully";
} else {
    // Error message
    echo "Error updating base marker position: " . $conn->error;
}

$conn->close();
?>
