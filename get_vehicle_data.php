<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select vehicle data from the database
$sql = "SELECT id, name, cargo, status, latitude, longitude FROM rescuer_vehicle";

// Perform the query
$result = mysqli_query($conn, $sql);

// Check if there are any results
if (mysqli_num_rows($result) > 0) {
    // Fetch data and store it in an array
    $vehicles = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $vehicles[] = $row;
    }

    // Return the array as JSON
    echo json_encode($vehicles);
} else {
    // No vehicles found
    echo json_encode([]);
}

// Close the database connection
mysqli_close($conn);
?>
