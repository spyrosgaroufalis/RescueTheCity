<?php
include"security_cityzen.php";

session_start();
include "security_cityzen.php";

if(isset($_POST['id']) && isset($_SESSION["username"]) && isset($_POST['latitude']) && isset($_POST['longitude'])) {
    $id = $_POST['id'];
    $username = $_SESSION["username"];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Retrieve product names and numbers from the announcements table based on the id
    $conn = mysqli_connect("localhost", "root", "", "help_city1");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve product names and numbers from announcements table
    $announcementQuery = "SELECT `name`, `needed_num` FROM `announcements` WHERE `id` = '$id'";
    $announcementResult = $conn->query($announcementQuery);
    $row = $announcementResult->fetch_assoc();
    $productNames = $row['name'];
    $productNumbers = $row['needed_num'];

    // Get the current date
    $currentDate = date("Y-m-d");

    // Retrieve phone number of the user from the user table
    $phoneQuery = "SELECT `phone_num` FROM `user` WHERE `username` = '$username'";
    $phoneResult = $conn->query($phoneQuery);
    $row = $phoneResult->fetch_assoc();
    $phoneNumber = $row['phone_num'];

    // Insert id, username, latitude, longitude, product names, and product numbers into help_offering table
    $sql = "INSERT INTO `help_offering`(`id`, `username`, `accepted`, `date`, `latitude`, `longitude`, `phone_num`, `product_name`, `product_num`, `date_of_comp`) 
            VALUES ('$id', '$username', 'expected', '$currentDate', '$latitude', '$longitude', '$phoneNumber', '$productNames', '$productNumbers', NULL)";

    if ($conn->query($sql) === TRUE) {
        echo "Record inserted successfully";
    } else {
        echo "Error inserting record: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Error: Required data not set.";
}
?>
