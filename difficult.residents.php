<?php
include"security_cityzen.php";
?>

<?php
//difficult.php
// Connect to the database
$conn = mysqli_connect("localhost","root","","help_city1");

$products = array(); // Define $products as an empty array

if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    // Retrieve product names from the database
    $sql = "SELECT `name` FROM `product` WHERE `name` not like'';";
    $result = $conn->query($sql);

    // Store product names in an array
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row["name"];
        }
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
            <input type="radio" name="selectedProducts" value="<?php echo $product; ?>"><?php echo $product; ?><br>
        <?php endforeach; ?>
        <p>Please insert the number of people that are with you:</p>
        <textarea id="numPeople" oninput="checkInput()"></textarea><br>
        <button id="submitSelection" disabled>Publish Announcement</button>
    </div>
    <script>
    function checkInput() {
        var numPeople = document.getElementById('numPeople').value;
        if (!isNaN(numPeople) && numPeople.trim() != '') {
            document.getElementById('submitSelection').disabled = false;
        } else {
            document.getElementById('submitSelection').disabled = true;
        }
    }

    document.getElementById('selectProducts').addEventListener('click', function() {
        document.getElementById('productList').style.display = 'block';
    });

    document.getElementById('submitSelection').addEventListener('click', function() {
        var selectedProduct = document.querySelector('input[name="selectedProducts"]:checked');
        var selectedProductName = selectedProduct ? selectedProduct.value : null;
        var numPeople = document.getElementById('numPeople').value;
        console.log(selectedProductName, numPeople); // Add this line

        // Send selectedProductName and numPeople to the server to store in the database
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "store_products.residents.php", true);
        xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert('Data storage successful');
            } else if (xhr.readyState === 4) {
                alert('Data storage failed');
            }
        };
        xhr.send(JSON.stringify({productName: selectedProductName, numberOfPeople: numPeople}));

        // Hide the product list
        document.getElementById('productList').style.display = 'none';
    });
</script>
</body>
</html>