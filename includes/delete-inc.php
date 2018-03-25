<?php

session_start();



	include 'connectiondata.php';

	$delete = mysqli_real_escape_string($conn, $_POST['delete']);
	//error handler
	//check if empty
	$house = $_GET['house_id'];
	
   
		$sql = "DELETE FROM house WHERE house_id = '$house' ";


		$result = mysqli_query($conn, $sql);

		header("Location: ../mylistings.php");


?>

