<?php

// if(isset($_POST['submit'])){
	session_start();

	include_once 'connectiondata.php';

	$first = mysqli_real_escape_string($conn, $_POST['first']);
	$last = mysqli_real_escape_string($conn, $_POST['last']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$uid = mysqli_real_escape_string($conn, $_POST['uid']);
	$pwd = mysqli_real_escape_string($conn, $_POST['pwd']);


	$first_error = $last_error = $email_error = $uid_error = $zip_error = "";
    $error = 0;

	//error handlers
	//check for empty fields

	if(empty($first) || empty($last) || empty($email) || empty($uid) || empty($pwd)) {
		header("Location: ../signup.php?signup=empty");
		exit();

	} else {
		//CHECK IF INPUT CHARS ARE VALID
		if(!preg_match("/^[a-zA-Z]*$/", $first) || !preg_match("/^[a-zA-Z]*$/", $last)){
			header("Location: ../signup.php?signup=invalid_char");
			exit();

		} else {
			//check if valid email
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				header("Location: ../signup.php?signup=invalid_email");
				exit();

			} else {
				$sql = "SELECT * FROM users WHERE user_uid = '$uid' OR user_email = '$uid' ";
				$result = mysqli_query($conn, $sql);
				$resultCheck = mysqli_num_rows($result);

				if($resultCheck > 0){
					header("Location: ../signup.php?signup=user_taken");
					exit();
				} else {
					//hash password
					$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
					//insert user to database
					$sql = "INSERT INTO users (user_first, user_last, user_email, user_uid, user_pwd) 
							VALUES ('$first','$last','$email','$uid','$hashedPwd');";

					$result = mysqli_query($conn, $sql)
					or die(mysqli_error($conn));

					// header("Location: ../signupsuccess.php");

						//error handler
						//check if empty

						if(empty($uid) || empty($pwd)){
							
							header("Location: ../index.php?login=empty");
							exit();
						} else {
							$sql = "SELECT * FROM users WHERE user_uid = '$uid' ";
							$result = mysqli_query($conn, $sql);
							$resultCheck = mysqli_num_rows($result);
							if($resultCheck < 1){
								header("Location: ../index.php?login=error");
								exit();

							} else {
								if ($row = mysqli_fetch_assoc($result)){
									//dehash password
									$hashedPwdCheck = password_verify($pwd, $row['user_pwd']);
									if($hashedPwdCheck == false) {
										header("Location: ../index.php?login=error");
										exit();
									} elseif ($hashedPwdCheck == true){
										//Log in the user here
										$_SESSION['u_id'] = $row['user_id'];
										$_SESSION['u_first'] = $row['user_first'];
										$_SESSION['u_last'] = $row['user_last'];
										$_SESSION['u_email'] = $row['user_email'];
										$_SESSION['u_uid'] = $row['user_uid'];
										
										header("Location: ../signupsuccess.php");
										exit();
									}
								}
							}
						}

					}
				}
			}
		}


mysqli_close($conn);

// }else{
// 	header("Location: ../index.php");
// 	exit();
// }

?>



