<?php
include"security.php";
?>

<?php
//difficult.php
// Connect to the database
$conn = mysqli_connect("localhost","root","","help_city1");

if(!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

// Retrieve product names from the database
$sql = "SELECT `name` FROM `product` WHERE `name` not like'';";
$result = $conn->query($sql);

// Store product names in an array
$products = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row["name"];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Selection</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <button id="selectProducts">Select Products That Are Needed</button>
    <div id="productList" style="display: none;">
        <form id="announcementForm">
            <input type="date" id="announcementDate" name="announcementDate" required><br> <!-- Add a date input field -->
            <?php foreach($products as $product): ?>
                <input type="checkbox" name="selectedProducts[]" value="<?php echo $product; ?>"><?php echo $product; ?><br>
            <?php endforeach; ?>
            <label for="numNeeded">Type the number that is needed:</label><br>
            <input type="text" id="numNeeded" name="numNeeded"><br> <!-- Add an input field for the number needed -->
            <button type="button" id="submitSelection">Publish Announcement</button> <!-- Change type to "button" to prevent form submission -->
        </form>
    </div>
    <script>
    document.getElementById('selectProducts').addEventListener('click', function() {
        document.getElementById('productList').style.display = 'block';
    });

    document.getElementById('submitSelection').addEventListener('click', function() {
        var selectedProducts = document.querySelectorAll('input[name="selectedProducts[]"]:checked');
        var selectedProductNames = Array.from(selectedProducts).map(function(product) {
            return product.value;
        });
        var announcementDate = document.getElementById('announcementDate').value; // Get the selected date
        var numNeeded = document.getElementById('numNeeded').value; // Get the number needed
        console.log(selectedProductNames, announcementDate, numNeeded); // Add this line

        // Send selectedProductNames, announcementDate, and numNeeded to the server to store in the database
        var formData = new FormData();
        formData.append('selectedProducts', JSON.stringify(selectedProductNames));
        formData.append('announcementDate', announcementDate);
        formData.append('numNeeded', numNeeded); // Append numNeeded to the form data

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "store_products.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert('Data storage successful');
            } else if (xhr.readyState === 4) {
                alert('Data storage failed');
            }
        };
        xhr.send(formData);

        // Hide the product list
        document.getElementById('productList').style.display = 'none';
    });
    </script>
</body>
</html>
