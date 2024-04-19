<?php
include"security.php";
?>

<?php


// delete_id.php
$con = mysqli_connect("localhost","root","","help_city1");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST["id"];
        
        // Check if database connection is open
        if ($con) {
            // Send MySQL query to delete customer with the given ID
            $sql3 = "DELETE FROM product WHERE `product`.`id`= $id";
            $sql4 = "DELETE FROM `details` WHERE  `details`.`id`= $id";
            //if ($con->query($sql4) === TRUE) {
            //echo "Detail record deleted successfully";
            //echo "<br>";
	   // echo "<br>"; 
           //                                  }
            //else {
          //  echo "Error deleting detail record: " . $con->error; 
           echo "<br>";
	  //  echo "<br>"; 
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