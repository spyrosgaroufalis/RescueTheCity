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
        <?php foreach($products as $product): ?>
            <input type="checkbox" name="selectedProducts[]" value="<?php echo $product; ?>"><?php echo $product; ?><br>
        <?php endforeach; ?>
        <button id="submitSelection">Publish Announcement</button>
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
console.log(selectedProductNames); // Add this line
            // Send selectedProductNames to the server to store in the database
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "store_products.php", true);
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.send(JSON.stringify(selectedProductNames));
        });
  
    </script>
</body>
</html>