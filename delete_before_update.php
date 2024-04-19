<?php
include"security.php";
?>

<?php
//delete_before_update.php

// Delete data from `product` table
$con = mysqli_connect("localhost","root","","help_city1");

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
} 
$deleteProductQuery = "DELETE FROM `product`";
// Execute the delete query
mysqli_query($con, $deleteProductQuery);

// Delete data from "details" table
$deleteDetailsQuery = "DELETE FROM `details`";
// Execute the delete query
mysqli_query($con, $deleteDetailsQuery);
//sleep(5);
//mysqli_close($con);
//Include the file "web_take_file_from_local_json5.php"


include "web_take_file_from_local_json5.php";


//sleep(5);
include "ajax1.html";

//include"delete_id.html";
//include"update.html";

?>