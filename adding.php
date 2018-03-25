<?php



include('includes/connectiondata.php');
// include('')

// $conn = mysqli_connect('ix.cs.uoregon.edu','ybl','413290')

// if(!$conn){
// 	echo 'not connected to server';
// }
// if(!mysql_select_db($conn, housemap)){
// 	echo 'not selected';
// }



$Address = $_POST['address'];
$Apt = $_POST['apt'];
$City = $_POST['city'];
$State = $_POST['state'];
$Zip = $_POST['zip'];
$bed = $_POST['bed'];
$bath = $_POST['bath'];
$sqft = $_POST['sqft'];
$hometype = $_POST['hometype'];
$price = $_POST['price'];



$address = mysqli_real_escape_string($conn, $address);

$sql =  "INSERT INTO house (address, apt_number, city, state, zip, price, bed, bath, square_feet, home_type) 
      VALUES ('$Address','$Apt','$City','$State', '$Zip','$price','$bed','$bath','$sqft','$hometype')";

$result = mysqli_query($conn, $sql)
or die(mysqli_error($conn));




header("refresh:2; url:index.html");

mysqli_close($conn);



?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="refresh" content="2;url = index.php">
</head>
</html>




