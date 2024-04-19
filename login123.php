<?php
// login123.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$timeout = 240; // 4 minutes
// Update the timeout every time the user performs an action
$_SESSION['timeout'] = time() + $timeout;
//session_start();
//$timeout = 240; // 4 minutes
//$_SESSION['timeout'] = time() + $timeout;
//$_SESSION['LAST_ACTIVITY'] = time();
// Retrieve the username and password from the form
$username = $_POST['username'];
$password = $_POST['password'];

// Connect to the database
$con = mysqli_connect("localhost","root","","help_city1");

if(!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }
if (time() > $_SESSION['timeout']) {
    // Destroy the session and redirect to login page
    session_unset();
    session_destroy();
    header('Location: web_initial_page.html');
    exit();
}

// Query the database to check if the username and password combination exists
$sql = "SELECT * FROM administrator WHERE username = '$username' AND password = '$password'";
$result = $con->query($sql);
$sql1 = "SELECT * FROM rescuer WHERE username = '$username' AND password = '$password'";
$result1 = $con->query($sql1);
$sql2 = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
$result2 = $con->query($sql2);




// If a row is returned, the combination exists
if ($result->num_rows > 0) {
   $_SESSION['username'] = $username;
   $_SESSION['password'] = $password;
 if (isset($_SESSION['username'])) {
 	

     //include "administration_choose_products.php";
     include "administrator_start_page.php";
     //include"delete_id.html";
     //include"update.html";
      
 
     
     }
else {echo "Invalid username or password.";}
} 







else {


if ($result1->num_rows > 0) {
   $_SESSION['username'] = $username;
   $_SESSION['password'] = $password;
 if (isset($_SESSION['username'])) {
 	

    
      include"rescuers_initial_page.php";

     
     }
else {echo "Invalid username or password.";}
} 

else{
if ($result2->num_rows > 0) {
   $_SESSION['username'] = $username;
   $_SESSION['password'] = $password;
 if (isset($_SESSION['username'])) {
 	

      include"resident_initial_page.php";
     

     }
else {echo "Invalid username or password.";}
} 
}
    
}


// Close the database connection
$con->close();
?>


