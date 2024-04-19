<?php
// Include your security measures if necessary
include "security_rescuer.php";

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch latitude and longitude data from the help_offering table
$sql = "SELECT * FROM help_offering";
$result = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
   
}

// Close the database connection
mysqli_close($conn);

// Set the content type to JSON
header('Content-Type: application/json');

// Output the JSON data
echo json_encode($data);
?>
