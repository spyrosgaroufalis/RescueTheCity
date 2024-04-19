<?php
include"security_cityzen.php";
?>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Receive the selected product names and number of people from the client-side
$data = json_decode(file_get_contents('php://input'), true);
$selectedProductName = $data['productName'];
$num_people = $data['numberOfPeople'];

print_r($data);
error_log(print_r($data, true)); // Add this line


session_start();
$username = $_SESSION["username"];
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Prepare and execute the SQL query to store selected product names in the database
$sql = "INSERT INTO `announcements_cityzen` (`id`,`name`,`num_people`,`username`) VALUES (null, '$selectedProductName','$num_people','$username')";

if ($conn->query($sql) === TRUE) {
    echo "Record inserted successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

// Print "hi"
echo "hi";
?>