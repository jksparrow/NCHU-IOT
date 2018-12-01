<?php 
$address = "";
if(!empty($_GET['address'])){
  $address = $_GET['address'];
}
//$address = '中興大學';

//info ==user, database
$servername = "localhost";
$username = "test123";
$password = "test123";
//database information
$databaseName = "iot";
$tableName = "light";
//connect dbms

$con=mysqli_connect($servername, $username, $password,$databaseName);
mysqli_query($con,"SET CHARACTER SET 'utf8'");
//mysqli_query($conn,"SET SESSION collation_connection ='utf8_unicode_ci'");
if ($con->connect_error) {
    die("Too bad!!!! Connection failed: " . $con->connect_error);
} 
//echo "Connected successfully !!!! Yayaya.=======...";

//connect db
// Change database to "test"
mysqli_select_db($con,$tableName);

//select
//Query database for data
 
$result= mysqli_query($con,"SELECT DISTINCT * from $tableName where address ='$address'");
  //store matrix
  $i=0;
  while ($row = mysqli_fetch_array($result,MYSQLI_NUM)){
    $data[$i]=$row;
    $i++;
  }

  //echo result as json 
    echo json_encode($data);

//close db
mysqli_close($con);

 ?>


