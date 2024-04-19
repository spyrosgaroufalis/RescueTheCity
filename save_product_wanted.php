<?php

include "security_cityzen.php";

if(isset($_SESSION["username"]) ) {
   
    $username = $_SESSION["username"];
  

// Receive the selected product names, number of people, current date, latitude, and longitude from the client-side
$data = json_decode(file_get_contents('php://input'), true);
$selectedProductName = $data['productName'];
$numPeople = $data['numberOfPeople'];
$currentDate = $data['currentDate'];
$latitude = $data['latitude'];
$longitude = $data['longitude'];
$numProducts = $data['numberOfProducts'];



// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
 // Retrieve phone number of the user from the user table
 $phoneQuery = "SELECT `phone_num` FROM `user` WHERE `username` = '$username'";
 $phoneResult = $conn->query($phoneQuery);
 $phone = $phoneResult->fetch_assoc();
 $phoneNumber = $phone['phone_num'];


// Prepare and execute the SQL query to insert the data into the announcements_cityzen table
$sql = "INSERT INTO `announcements_cityzen` (`id`, `name`, `have_seen_date`, `num_people`, `accepted`, `date_of_response`, `date_of_comp`, `username`, `lat_cit`, `lon_cit`, `num_products`,`phone_num`) 
        VALUES (NULL, '$selectedProductName', '$currentDate', '$numPeople', 'expected', NULL, NULL, '$username', '$latitude', '$longitude', '$numProducts', '$phoneNumber')";

error_log($sql);

if ($conn->query($sql) === TRUE) {
    echo "Record inserted successfully!" . $sql;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
}
else {
    echo "Error: Required data not set.";
}
?>
