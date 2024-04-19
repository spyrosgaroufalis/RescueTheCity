
<?php
include"security.php";

// Receive the selected product names, announcement date, and number needed from the client-side
$selectedProductNames = json_decode($_POST['selectedProducts'], true);
$announcementDate = $_POST['announcementDate'];
$numNeeded = $_POST['numNeeded']; // Retrieve the number needed from the form

$selectedProductNamesJson = json_encode($selectedProductNames);

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Prepare and execute the SQL query to store selected product names, announcement date, and number needed in the database
$sql = "INSERT INTO `announcements` (`id`, `name`, `date`, `needed_num`) VALUES (null, '$selectedProductNamesJson', '$announcementDate', '$numNeeded')";

if ($conn->query($sql) === TRUE) {
    echo "Record inserted successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
