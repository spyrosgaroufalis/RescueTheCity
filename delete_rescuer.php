<?php
include"security.php";
?>

<?php
// change_quantity.php
$con = mysqli_connect("localhost","root","","help_city1");


// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Retrieve data from the form
$username = $_POST['username'];
//$password = $_POST['password'];

// Prepare and execute the SQL query
$sql = "DELETE FROM `rescuer` WHERE `username`='$username';";

$message = "Database communication was succesful : Press ok to continue...";
$message1 = "Database communication was unsuccessful : ";           
if ($con->query($sql) === TRUE) { 
            echo "<script type='text/javascript'>alert('$message');</script>";
                                 } else {
            echo "<script type='text/javascript'>alert('$message1'+'. $con->error');</script>"; 
                                        }

// Close the connection
$con->close();
include"administrator_start_page.php";
?>