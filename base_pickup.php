


<?php
include "security_rescuer.php";

if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];

    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "help_city1");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch the latitude and longitude of the rescuer's vehicle
    $sqlVehiclePosition = "SELECT latitude, longitude FROM rescuer_vehicle WHERE name = '$username'";
    $resultVehiclePosition = mysqli_query($conn, $sqlVehiclePosition);
    $rowVehiclePosition = mysqli_fetch_assoc($resultVehiclePosition);
    $vehicleLatitude = $rowVehiclePosition['latitude'];
    $vehicleLongitude = $rowVehiclePosition['longitude'];

    // Fetch the latitude and longitude of the base position
    $sqlBasePosition = "SELECT latitude, longitude FROM base_marker_position WHERE id = 1";
    $resultBasePosition = mysqli_query($conn, $sqlBasePosition);
    $rowBasePosition = mysqli_fetch_assoc($resultBasePosition);
    $baseLatitude = $rowBasePosition['latitude'];
    $baseLongitude = $rowBasePosition['longitude'];

    // Calculate the distance between the vehicle and the base
    $distance = calculateDistance1($vehicleLatitude, $vehicleLongitude, $baseLatitude, $baseLongitude);

    // Check if the vehicle is within 100 meters of the base
    $showButton = ($distance <= 0.1); // 100 meters in kilometers

    // Fetch all products from the database
    $sqlProducts = "SELECT `name` FROM `product` WHERE `name` != ''";
    $resultProducts = mysqli_query($conn, $sqlProducts);


    // Close database connection
    mysqli_close($conn);
}else {
    echo "Error: Required username not set.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Big Button Example</title>
</head>
<body>

<?php
// Display the button if the condition is met
if ($showButton) {
    echo "<p style=\"font-size: 25pt;\">Base is Nearby!</p>";
    
    // Display products with radio buttons and input fields
    echo '<form id="productForm" action="" method="POST">';
    while ($rowProduct = mysqli_fetch_assoc($resultProducts)) {
        $productName = $rowProduct['name'];
        echo '<label>';
        echo '<input type="checkbox" name="selectedProducts[]" value="' . $productName . '">' . $productName;
        echo '</label>';
        echo '<input type="number" name="' . $productName . '" placeholder="Quantity"><br>';
    }
    echo '<Button onclick=\"AddToCargo()\" style="font-size: 24px;">Φόρτωση</button>';
    echo '</form>';
}

echo "<script>
    function AddToCargo() {
        // Get the form element
        const form = document.getElementById('productForm');

        // Create an object to store the selected products and quantities
        const selectedProducts = {};

        // Loop through each checkbox and input field in the form
        form.querySelectorAll('input[type=checkbox]').forEach(checkbox => {
            if (checkbox.checked) {
                // Get the product name
                const productName = checkbox.value;
                // Get the quantity input field associated with the product
                const quantityInput = form.querySelector('input[name=' + productName + ']');
                // Get the quantity value
                const quantity = quantityInput.value;
                // Add the product and quantity to the selectedProducts object
                selectedProducts[productName] = quantity;
            }
        });

        // Get the rescuer name
        const rescuerName = '". addslashes($username) ."';

        // Send request to update status in the database
        fetch('AddToCargo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                rescuer_name: rescuerName,
                selected_products: selectedProducts
            }),
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

</body>
</html>

<?php
// Function to calculate distance between two points using Haversine formula
function calculateDistance1($lat1, $lon1, $lat2, $lon2) {
    $R = 6371; // Radius of the Earth in kilometers
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $d = $R * $c; // Distance in kilometers
    return $d;
}
?>
