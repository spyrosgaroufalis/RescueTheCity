<?php
// Include your security measures if necessary
include "security_rescuer.php";
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch announcements from the database table
$query = "SELECT * FROM announcements_cityzen";
$result = $conn->query($query);

if (!$result) {
    die("Database query failed.");
}

// Array to store the fetched data
$data = array();

// Fetching rows from the result set
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Close the database connection
$conn->close();

// Sending the data as JSON response
header('Content-Type: application/json');
echo json_encode($data);
?>
