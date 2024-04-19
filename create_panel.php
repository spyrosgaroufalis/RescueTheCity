


<?php
// Include your security measures if necessary
include "security_rescuer.php";

if(isset($_SESSION["username"]) ) {
   
    $username = $_SESSION["username"];
  

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "help_city1");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch vehicle's latitude and longitude
$sql = "SELECT latitude, longitude FROM rescuer_vehicle WHERE name = '$username'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$vehicleLatitude = $row['latitude'];
$vehicleLongitude = $row['longitude'];

// Query to fetch data from the help_offering table where accepted is "waiting"
$sqlHelpOffering = "SELECT * FROM help_offering WHERE accepted = 'waiting'";
$resultHelpOffering = mysqli_query($conn, $sqlHelpOffering);

// Query to fetch data from the announcements_cityzen table where accepted is "waiting"
$sqlAnnouncementsCityzen = "SELECT * FROM announcements_cityzen WHERE accepted = 'waiting'";
$resultAnnouncementsCityzen = mysqli_query($conn, $sqlAnnouncementsCityzen);
}
else {
    echo "Error: Required data not set.";
}

?>

<!-- Display the panel -->
<!-- Display the panel -->
<div>
    <h3>Help Offerings</h3>
    <table border="1">
        <tr>
            <th>Username</th>
            <th>Date</th>
            <th>Phone Number</th>
            <th>Product Name</th>
            <th>Product Number</th>
            <th>Actions</th> <!-- New column for buttons -->
        </tr>
        <?php
// Display data from help_offering table
while ($row = mysqli_fetch_assoc($resultHelpOffering)) {
    // Calculate distance between task location and vehicle location
    $distance = calculateDistance($row['latitude'], $row['longitude'], $vehicleLatitude, $vehicleLongitude);
    echo "<tr>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['date'] . "</td>";
            echo "<td>" . $row['phone_num'] . "</td>";
            echo "<td>" . $row['product_name'] . "</td>";
            echo "<td>" . $row['product_num'] . "</td>";
            echo "<td>";
    // Check if the distance is within 50 meters
    if ($distance <= 0.05) { // Convert 50 meters to kilometers
        // Display buttons only if the condition is true
        
                echo "<button onclick=\"completeOffer(" . $row['id'] . ")\" style='margin-right: 5px;'>Ολοκλήρωση</button>";
                
             
    } 
    echo "<button onclick=\"deleteOffer(" . $row['id'] . ")\">Ακύρωση</button>";
    echo "</td>"; // End of the "Actions" cell
    echo "</tr>";
}

echo "<script>
    function deleteOffer(id) {

        const rescuerName = '". addslashes($username) ."';

        
        // Send request to update status in the database
        fetch('delete_offer.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id, rescuer_name: rescuerName}),
            })
        .then(response => response.text())
        .then(data => {
            console.log(data); // Log success message or error message from the server
        })
        .catch(error => {
            console.error('Error updating status:', error);
        });
    }
</script>";

echo "<script>
    function completeOffer(id) {

        const rescuerName = '". addslashes($username) ."';

        // Send request to update status in the database
        fetch('complete_offer.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id, rescuer_name: rescuerName}),
            })
        .then(response => response.text())
        .then(data => {
            console.log(data); // Log success message or error message from the server
        })
        .catch(error => {
            console.error('Error updating status:', error);
        });
    }
</script>";
?>



    </table>

    <h3>Announcements</h3>
    <table border="1">
        <tr>
            <th>Username</th>
            <th>Date</th>
            <th>Phone Number</th>
            <th>Product Name</th>
            <th>Product Number</th>
            <th>Actions</th> <!-- New column for buttons -->
        </tr>
        <?php
// Display data from help_offering table
while ($row = mysqli_fetch_assoc($resultAnnouncementsCityzen)) {
    // Calculate distance between task location and vehicle location
    $distance = calculateDistance($row['lat_cit'], $row['lon_cit'], $vehicleLatitude, $vehicleLongitude);
    echo "<tr>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['have_seen_date'] . "</td>";
            echo "<td>" . $row['phone_num'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['num_products'] . "</td>";
            echo "<td>";
            // Check if the distance is within 50 meters
            if ($distance <= 0.05) { // Convert 50 meters to kilometers
                // Display buttons only if the condition is true
                
                        echo "<button onclick=\"completeAnnouncement(" . $row['id'] . ")\" style='margin-right: 5px;'>Ολοκλήρωση</button>";
                       
                     
            } 
            echo "<button onclick=\"deleteAnnouncement(" . $row['id'] . ")\">Ακύρωση</button>";

            echo "</td>"; // End of the "Actions" cell
            echo "</tr>";

    
}
echo "<script>
    function deleteAnnouncement(id) {

        const rescuerName = '". addslashes($username) ."';

        // Send request to update status in the database
        fetch('delete_announcement.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id, rescuer_name: rescuerName }),
            })
        .then(response => response.text())
        .then(data => {
            console.log(data); // Log success message or error message from the server
        })
        .catch(error => {
            console.error('Error updating status:', error);
        });
        
    }
</script>";

echo "<script>
    function completeAnnouncement(id) {

        const rescuerName = '". addslashes($username) ."';

        // Send request to update status in the database
        fetch('complete_announcement.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id, rescuer_name: rescuerName}),
            })
        .then(response => response.text())
        .then(data => {
            console.log(data); // Log success message or error message from the server
        })
        .catch(error => {
            console.error('Error updating status:', error);
        });
    }
</script>";
?>


    </table>
</div>

<?php
// Function to calculate distance between two points using Haversine formula
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $R = 6371; // Radius of the Earth in kilometers
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a =
        sin($dLat / 2) * sin($dLat / 2) +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
        sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $d = $R * $c; // Distance in kilometers
    return $d;
}


?>



