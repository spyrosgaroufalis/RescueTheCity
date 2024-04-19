<?php
// At the start of each included file
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username']) || time() > $_SESSION['timeout']) {
    // Destroy the session and redirect to login page
    session_destroy();
    header('Location: web_initial_page.html');
    exit();
}
// Rest of your code
?>


<?php
// administration_choose_products.php
$con = mysqli_connect("localhost","root","","help_city1");
$result=null;
$roww=null;
$sqld=null;
$sql2 =null;
$row1=null;
$result1=null;
// Check if there is an open database connection
if ($con) {
    // Query the database to retrieve all data from the "product" table
    $id='';
    $sqld = "SELECT * FROM `product` WHERE `name` not like''";
    $result = $con->query($sqld);
                                //$sql1 = "SELECT * FROM `categories` WHERE `category_name` not like''";
                                // If there are rows returned, display the data
    if ($result->num_rows > 0) {
        while ($roww = $result->fetch_assoc()) {
            // Display the data from each row
            $id=$roww['id'];
            echo "Product ID: " . $roww['id'] . "<br>";
            echo "Product Name: " . $roww['name'] . "<br>";
            if(is_null($roww['quantity'])){
                     echo "Quantity: 0 " . "<br>";}
            else{
	    echo "Quantity: " . $roww['quantity'] . "<br>";
                 }
            $sql2 = "SELECT * FROM `categories`  WHERE $id =`id`";
            $result1 = $con->query($sql2);
            if ($result->num_rows > 0) {
                 while ($row1 = $result1->fetch_assoc()) {
            echo "Product Category: " . $row1['category_name'] . "<br>"; 
            
            echo "<br>";
                     }}
            echo "<br>";
	    echo "<br>";
	    echo "<br>";
        }
    } else {
        echo "No products found.";
    }
} else {
    echo "No open database connection.";
}

?>