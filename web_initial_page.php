<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <style>
        body {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
        }
        
        .container {
            width: 300px;
            margin: 0 auto;
            margin-top: 100px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="web_initial_page.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>



<?php
// login.php

// Retrieve the username and password from the form
$username = $_POST['username'];
$password = $_POST['password'];

// Connect to the database
$con = mysqli_connect("localhost","root","","help_city1");

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}


// Query the database to check if the username and password combination exists
$sql = "SELECT * FROM administrator WHERE username = '$username' AND password = '$password'";
$result = $con->query($sql);

// If a row is returned, the combination exists
if ($result->num_rows > 0) {
    echo "Login successful!";
} else {
    echo "Invalid username or password.";
}

// Close the database connection
$con->close();
?>




