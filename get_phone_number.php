<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the username from the request parameters
$username = $_GET['username'];

// Query to fetch the phone number based on the username
$query = "SELECT phone_num FROM user WHERE username = '$username'";
$result = $conn->query($query);

if (!$result) {
    die("Database query failed: " . $conn->error);
}

// Check if a row was returned
if ($result->num_rows > 0) {
    // Fetch the phone number
    $row = $result->fetch_assoc();
    $phoneNumber = $row['phone_num'];
    
    // Return the phone number
    echo $phoneNumber;
} else {
    // If no row was returned, return an empty string or an appropriate response
    echo "";
}

// Close the database connection
$conn->close();
?>
