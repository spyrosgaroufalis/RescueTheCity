<?php
include"security.php";
?>

<?php


// change_quantity.php
$con = mysqli_connect("localhost","root","","help_city1");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST["id"];
        $quantity = $_POST["quantity"];
        // Check if database connection is open
        if ($con) {
            // Send MySQL query to delete customer with the given ID
            
            $sql3= "UPDATE product SET `product`.`quantity` = $quantity WHERE `product`.`id`= $id";
            
            //if ($con->query($sql3) === TRUE) {
           // echo "Quantity record changed successfully";
           // echo "<br>";
	   // echo "<br>"; 
            //                                 }
            //else {
           // echo "Error changing quantity record: " . $con->error; 
           // echo "<br>";
	   // echo "<br>"; 
                                                   }
           
$message = "Database communication was succesful : Press ok to continue...";
$message1 = "Database communication was unsuccessful : ";           
if ($con->query($sql3) === TRUE) { 
            echo "<script type='text/javascript'>alert('$message');</script>";
                                 } else {
            echo "<script type='text/javascript'>alert('$message1'+'. $con->error');</script>"; 
                                        }
        }
//$con->close();
//include"web_initial_page.html";
//ob_end_flush();
include"administrator_start_page.php";
//include"delete_id.html";
//include"update.html";

 //}

?>