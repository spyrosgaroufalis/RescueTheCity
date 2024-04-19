<?php
include"security_rescuer.php";
?>


<?php



// delete_help_offer.php
$conn = mysqli_connect("localhost", "root", "", "help_city1");

$new_id = "";
$new_us = "";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_POST['id_original'];

// Get the current date
$currentDate = date("Y-m-d");

// Prepare and execute the SQL query
$sqlOO = "SELECT * FROM `help_offering` WHERE `id_original`='$id';";
$result = $conn->query($sqlOO);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $new_id = $row["id"];
    $new_us = $row["username"];

    $sql = "UPDATE `help_offering` SET `id`='" . $row["id"] . "', `username`='" . $row["username"] . "', `accepted`='received', `id_original`='$id', `date_accepted`='$currentDate' WHERE `id_original`='$id';";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully!";
        echo "data: " . $new_us . "<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>