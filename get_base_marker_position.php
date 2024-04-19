<?php

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve base marker position
$sql = "SELECT latitude, longitude FROM base_marker_position WHERE id = 1"; // Assuming the base marker has ID 1

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the position as associative array
    $row = $result->fetch_assoc();
    $position = array(
        'latitude' => $row['latitude'],
        'longitude' => $row['longitude']
    );
    // Encode the position as JSON and output it
    echo json_encode($position);
} else {
    echo json_encode(array()); // Return empty JSON if no position found
}

$conn->close();
?>
