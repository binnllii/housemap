<?php



include('connectiondata.php');
// include('')

// $conn = mysqli_connect('ix.cs.uoregon.edu','ybl','413290')

// if(!$conn){
// 	echo 'not connected to server';
// }
// if(!mysql_select_db($conn, housemap)){
// 	echo 'not selected';
// }




	$Address = mysqli_real_escape_string($conn, $_POST['address']);
	$Apt = mysqli_real_escape_string($conn, $_POST['apt']);
	$City = mysqli_real_escape_string($conn, $_POST['city']);
	$State = mysqli_real_escape_string($conn, $_POST['state']);
	$Zip = mysqli_real_escape_string($conn, $_POST['zip']);
	$bed = mysqli_real_escape_string($conn, $_POST['bed']);
	$bath = mysqli_real_escape_string($conn, $_POST['bath']);
	$sqft = mysqli_real_escape_string($conn, $_POST['sqft']);
	$hometype = mysqli_real_escape_string($conn, $_POST['hometype']);
	$price = mysqli_real_escape_string($conn, $_POST['price']);

 if(empty($City) || empty($State) || empty($bed) || empty($bath) || 
 	empty($price)) {
 		header("Location: ../add.php?adding=empty");
		exit();
		// } else {
		// 	if(!preg_match("/^[a-zA-Z]*$/", $City) || !preg_match("/^[a-zA-Z]*$/", $State) || !preg_match("/^[a-zA-Z]*$/", $hometype)){
		// 	header("Location: ../add.php?add=invalid");
		// 	exit();
 		  //   } else {
//  			    $sql = "SELECT * FROM house WHERE address = '$Address' ";
//  		    	$result = mysqli_query($conn, $sql);
//  				$resultCheck = mysqli_num_rows($result);
//  		
//  				if($resultCheck > 0){
//  					header("Location: ../add.php?adding=address-added-already");
//  					exit();
 				} else {
					$address = mysqli_real_escape_string($conn, $address);

					$sql =  "INSERT INTO house (address, apt_number, city, state, zip, price, bed, bath, square_feet, home_type) 
      				VALUES ('$Address','$Apt','$City','$State', '$Zip','$price','$bed','$bath','$sqft','$hometype')";

					$result = mysqli_query($conn, $sql)
					or die(mysqli_error($conn));

					header("Location: ../listhomesuccess.php");
					exit();
 				}

mysqli_close($conn);



?>





