<?php
include"security_cityzen.php";
?>

<?php
// delete_help_offer.php
$con = mysqli_connect("localhost","root","","help_city1");


// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
$id = $_POST['id_original'];


// Prepare and execute the SQL query
$sql = "DELETE FROM `help_offering` WHERE `id_original`='$id';";

if ($con->query($sql) === TRUE) {
    echo "Record deleted successfully1111111111111!";
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}

// Close the connection
$con->close();

?>