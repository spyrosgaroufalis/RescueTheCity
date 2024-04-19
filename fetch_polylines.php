<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch polyline data from the database
$sql = "SELECT polyline_start_lat, polyline_start_lon, polyline_end_lat, polyline_end_lon FROM polylines_inprogress";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Create an array to store the polyline data
    $polylines = array();

    // Loop through the results and add them to the array
    while ($row = $result->fetch_assoc()) {
        $polylines[] = $row;
    }

    // Convert the array to JSON and output it
    echo json_encode($polylines);
} else {
    // If no results found, output an empty array
    echo json_encode(array());
}

// Close the database connection
$conn->close();
?>
