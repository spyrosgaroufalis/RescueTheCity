<?php
// change_quantity.php
$con = mysqli_connect("localhost","root","","help_city1");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Retrieve data from the form
$username = $_POST['username'];
$password = $_POST['password'];
$phone_num = $_POST['phone_num'];
$location = $_POST['location'];

// Check if the username already exists
$check = "SELECT * FROM `user` WHERE `username` = '$username'";
$checkResult = $con->query($check);

if ($checkResult->num_rows > 0) {
    // Username exists, display an alert message
    echo "<script>alert('Username already exists!');</script>";
} else {
    // Username does not exist, proceed with the SQL query
    $sql = "INSERT INTO `user`(`username`, `password`, `phone_num`, `location`) VALUES ('$username', '$password', '$phone_num', '$location')";

    $message = "Database communication was succesful : Press ok to continue...";
    $message1 = "Database communication was unsuccessful : ";           
if ($con->query($sql) === TRUE) { 
            echo "<script type='text/javascript'>alert('$message');</script>";
                                 } else {
            echo "<script type='text/javascript'>alert('$message1'+'. $con->error');</script>"; 
                                        }
        
}

// Close the connection
$con->close();
include"web_initial_page.html";
?>