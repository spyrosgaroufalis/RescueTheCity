<?php
include"security_cityzen.php";
?>

<?php
// show_announcements_resid.php
$conn = mysqli_connect("localhost", "root", "", "help_city1");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT `id`, `name`, `needed_num` FROM `announcements`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $id = $row["id"];
        echo "Announcement number: " . $id . "<br>";
        $names = json_decode($row["name"], true);
        echo "Product names needed: ";
        foreach($names as $name) {
            echo $name . " ";
        } 
        echo "<br>";
        echo "Number needed: " . $row["needed_num"] . "<br>"; // Display the number needed
        echo "<button id='btn$id' onclick='buttonClicked($id)'>Give Help</button>";
        echo "<br><br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>