<?php
include"security_rescuer.php";
?>

<?php
// show_help_histor_resid.php
$conn = mysqli_connect("localhost", "root", "", "help_city1");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$sql = "SELECT `id`, `id_original`, `accepted`, `username`, `date`, `date_accepted` FROM `help_offering`";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc() ) {
       // if ($row["username"] == $username) {
            echo "Application number: " . $row["id_original"]. "<br>";
            echo "Application state: " . $row["accepted"]. "<br>";
            echo "Date of the application set: " . $row["date"]. "<br>";
            echo "Date of the application acceptance: " . $row["date_accepted"]. "<br>";

            // Fetch the corresponding announcement from type 2
            $sql2 = "SELECT `name` FROM `announcements` WHERE `id` = " . $row["id"];
            $result2 = $conn->query($sql2);
            if ($result2->num_rows > 0) {
                while($row2 = $result2->fetch_assoc()) {
                    $names = json_decode($row2["name"], true);
                    echo "Product names needed: ";
                    foreach($names as $name) {
                        echo $name . " ";
                    }
                }
            }

            echo "<button id='btn1" . $row["id_original"] . "' onclick='buttonClicked1(" . $row["id_original"] . ")'>accept</button>";
            echo "<br><br>";
       // }
    }
} else {
    echo "0 applications";
}
$conn->close();
?>