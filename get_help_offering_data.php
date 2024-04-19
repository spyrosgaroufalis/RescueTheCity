<?php
// Include your security file if necessary
// include "security_rescuer.php";


// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "help_city1";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select data from the help_offering table
$sql = "SELECT * FROM help_offering";

// Perform the query
$result = $conn->query($sql);


// Check if the query was successful
if ($result->num_rows > 0) {
    
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

// Return the marker data as JSON
echo json_encode($data);
?>
