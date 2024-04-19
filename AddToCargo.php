<?php
include "security_rescuer.php";



// Retrieve JSON data sent from the frontend
$data = json_decode(file_get_contents("php://input"), true);

// Check if 'rescuer_name' parameter is provided in the request body
if (isset($data['rescuer_name'])) {
    // Extract the 'rescuer_name' parameter
    $rescuerName = $data['rescuer_name'];

    // Log the received rescuer name
    error_log("Received rescuer name: " . $rescuerName);

    // Check if 'selected_products' parameter is provided in the request body
    if (isset($data['selected_products'])) {
        // Extract the 'selected_products' parameter
        $selectedProducts = $data['selected_products'];

        // Log the received selected products
        error_log("Received selected products: " . json_encode($selectedProducts));

        // Connect to the database
        $conn = mysqli_connect("localhost", "root", "", "help_city1");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch current cargo of the rescuer vehicle
        $sqlCargo = "SELECT `cargo` FROM `rescuer_vehicle` WHERE `name` = '$rescuerName'";
        $resultCargo = mysqli_query($conn, $sqlCargo);
        $rowCargo = mysqli_fetch_assoc($resultCargo);
        $currentCargo = $rowCargo['cargo'];

        // Append selected products to the current cargo
        foreach ($selectedProducts as $productName => $quantity) {
            // Check if the product is not already in the cargo
            if (!strpos($currentCargo, $productName)) {
                // Append the product to the cargo with the specified quantity
                $currentCargo .= ", $productName ";
            }
        }

        // Update the cargo in the database
        $sqlUpdateCargo = "UPDATE `rescuer_vehicle` SET `cargo` = '$currentCargo' WHERE `name` = '$rescuerName'";
        if (mysqli_query($conn, $sqlUpdateCargo)) {
            echo "Cargo updated successfully";
        } else {
            echo "Error updating cargo: " . mysqli_error($conn);
        }

        // Close database connection
        mysqli_close($conn);
    } else {
        echo "Error: No selected products received";
    }
} else {
    // If the 'rescuer_name' parameter is not provided, return an error message
    echo "Error: 'rescuer_name' parameter not provided";
}
?>
