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

$sql = "SELECT `id`, `name`, `have_seen_date`, `num_people`, `accepted`, `date_of_response` , `date_of_comp` FROM `announcements_cityzen`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "Aplication number: " . $row["id"]. "<br>";
        echo "Product needed: " . $row["name"]. "<br>";
        echo "Date that someone saw the aplication: " . $row["have_seen_date"]. "<br>";
        echo "Number of people involved: " . $row["num_people"]. "<br>";
        echo "Aplication state: " . $row["accepted"]. "<br>";
        echo "Aplication number: " . $row["date_of_response"]. "<br>";
        echo "Is completed: " . $row["date_of_comp"]. "<br>";
       // foreach($names as $name) {
       //     echo $name . " ";
       // }
        echo "<br><br>";
    }
} else {
    echo "0 aplications";
}
$conn->close();
?>