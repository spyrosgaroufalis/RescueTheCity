<?php
// Include your security file if necessary
 include "security.php";

// Retrieve JSON data sent from the frontend
$data = json_decode(file_get_contents("php://input"), true);
// Check if 'id' parameter is provided in the request body
if (isset($data['rescuer_name'])) {


// Retrieve the rescuer's name from the frontend (assuming it's sent along with the 'id')
$rescuerName = $data['rescuer_name'];

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select data from the help_offering table
$query = "SELECT * FROM help_offering WHERE rescuer_name = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Database query failed " );
}

// Bind the parameter (rescuer name) to the prepared statement
$stmt->bind_param("s", $rescuerName);

// Execute the prepared statement
$stmt->execute();

// Get the result set
$result = $stmt->get_result();

if (!$result) {
    die("Error fetching result " );
}

// Array to store the fetched data
$data = array();

// Fetching rows from the result set
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Close the prepared statement
$stmt->close();

// Close the database connection
$conn->close();

// Sending the data as JSON response
header('Content-Type: application/json');
echo json_encode($data);

}else {
    // If 'id' parameter is missing, send an error response
    echo "Error: ";
}
?>
