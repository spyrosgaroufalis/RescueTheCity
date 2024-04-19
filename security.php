<?php
// At the start of each included file
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$username='';
$username=$_SESSION['username'];
$password='';
$password=$_SESSION['password'];
// Connect to the database
$con = mysqli_connect("localhost","root","","help_city1");

if(!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }
// Query the database to check if the username and password combination exists
$sql = "SELECT * FROM administrator WHERE username = '$username' AND password = '$password'";
$result = $con->query($sql);




$timeout = 240; // 4 minutes
// Update the timeout every time the user performs an action
$_SESSION['timeout'] = time() + $timeout;
if (!($result->num_rows > 0) || time() > $_SESSION['timeout']) {
    // Destroy the session and redirect to login page
    session_unset();
    session_destroy();
    header('Location: web_initial_page.html');
    exit();
}
// Close the database connection
//$con->close();
?>