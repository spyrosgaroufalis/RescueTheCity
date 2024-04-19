<?php
include "security_rescuer.php";

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch data from the announcements_cityzen table
$sql = "SELECT * FROM announcements_cityzen";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching data: " . mysqli_error($conn));
}

// Create an array to store marker data
$markers = [];

// Process each row of the result set
while ($row = mysqli_fetch_assoc($result)) {
    // Extract values from the row
    $id = $row['id'];
    $name = $row['name'];
    $have_seen_date = $row['have_seen_date'];
    $num_people = $row['num_people'];
    $accepted = $row['accepted'];
    $date_of_response = $row['date_of_response'];
    $date_of_comp = $row['date_of_comp'];
    $username = $row['username'];
    $lat = $row['lat']; // Latitude
    $lon = $row['lon']; // Longitude

    // Add marker data to the array including latitude and longitude
    $markers[] = [
        'id' => $id,
        'name' => $name,
        'have_seen_date' => $have_seen_date,
        'num_people' => $num_people,
        'accepted' => $accepted,
        'date_of_response' => $date_of_response,
        'date_of_comp' => $date_of_comp,
        'username' => $username,
        'lat' => $lat,
        'lon' => $lon
    ];
}

// Output JSON data
header('Content-Type: application/json');
echo json_encode($markers);

// Close the database connection
mysqli_close($conn);
?>
