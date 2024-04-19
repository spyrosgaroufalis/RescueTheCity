<?php
include"security.php";
?>

<?php
//web_take_file_from_local_json5.php


$con = mysqli_connect("localhost","root","","help_city1");

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
} 

$array='';
$detail='';
$row='';
$detailValue='';
$detailName ='';
$details ='';
$detail ='';
$query = '';
$query1 = '';
$query2 = '';
$table_data = '';
$filename = "internet_data.json";
$data = file_get_contents($filename); //Read the JSON file in PHP
$array = json_decode($data, true); //Convert JSON String into PHP Array


if (is_array($array)){
foreach($array['items'] as $row) //Extract the Array Values by using Foreach Loop
          {
$id=$row['id'];
$name=$row['name'];
$category=$row['category'];
$query .= "INSERT INTO product(id, category, name ) VALUES ('$id','$category','$name'); ";  // Make Multiple Insert Query 

foreach($row['details'] as $detail)
{
$detailName =$detail['detail_name'];
$detailValue=$detail['detail_value'];
$query .= "INSERT INTO details(id,detail_name,detail_value ) VALUES ('$id','$detailName','$detailValue'); ";

}
}
}


if (is_array($array)){


foreach($array['categories'] as $row){
$id=$row['id'];
$category_name=$row['category_name'];
$query .= "INSERT INTO categories(id, category_name ) VALUES ('$id','$category_name'); ";  // Make Multiple Insert Query
                                     }

}


$message1 = "Database communication was unsuccessful : ";
if(mysqli_multi_query($con, $query)) //Run Mutliple Insert Query
    		{ 
echo "<h3>Imported JSON Data</h3><br />";


                }



else // If $myList was not an array, then this block is executed. 
{
  echo "<script type='text/javascript'>alert('$message1'+'. $con->error');</script>";
}














$con->close();
//include"web_initial_page.html";
//header("Location: http://localhost:1030/new_web/login123.php");

//ob_end_flush();
//include"ajax.php";
//exit();
?>