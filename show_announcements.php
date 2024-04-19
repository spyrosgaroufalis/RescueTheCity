<?php
include"security_global.php";
?>

<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT `id`, `name`, `date`,`needed_num`  FROM `announcements`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "Announcement number: " . $row["id"]. "<br>";
        $names = json_decode($row["name"], true);
        echo "Product names needed: ";
        foreach($names as $name) {
            echo $name . " ";
        }
        echo "<br>";
        echo "Number needed: " . $row["needed_num"] . "<br>"; // Display the number needed

        echo "<br>Date of the announcement: " . $row["date"] . "<br><br>"; // Display the date of the announcement


        echo "<br><br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>