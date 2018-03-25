<?php
    include_once 'header.php';
?>

<?php
    include('includes/connectiondata.php');

    $first_error = $last_error = $email_error = $uid_error = $pass_error = $pass_match_error = "";
    $error = 0;

     if ($_SERVER['REQUEST_METHOD'] == "POST"){


            //for first name error
            if(empty($_POST['first'])){
                // echo 'Empty first name field';
                $first_error = "First name field is empty";
                $error = 1;
                
            } elseif (!preg_match("/^[a-zA-Z ]+$/", $_POST['first'])){
                $first_error = "First name must contain only letters";
                // echo "Error";
                $error = 1;
                $first = $_POST['first'];
            } else {
                // echo 'works';
                $first = mysqli_real_escape_string($conn, $_POST['first']);
            }

            //for last name error
            if(empty($_POST['last'])){
                // echo 'Empty last name field';
                $last_error = "Last name field is empty";
                $error = 2;
            } elseif (!preg_match("/^[a-zA-Z ]+$/", $_POST['last'])){
                $last_error = "Last name must ontain only letters";
                $last = $_POST['last'];
                $error = 2;
            } else {
                // echo 'works';
                $last = mysqli_real_escape_string($conn, $_POST['last']);
            }

            //for email error
            if(empty($_POST['email'])){
                // echo 'E-mail Address field is empty';
                $email_error = "Email Address field is empty";
                $error = 3;
            } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                $email_error = "Invalid email address";
                
                $error = 3;
            } else {
                // echo 'works';
                $email = mysqli_real_escape_string($conn, $_POST['email']);
            }

            $sql = "SELECT * FROM users WHERE user_email = '$email' ";
            $result = mysqli_query($conn, $sql);
            $resultCheck = mysqli_num_rows($result);

            if($resultCheck > 0){
                $email_error = "Email is taken try a different one";
                
                $error = 3;
            }

            //user id check

            if(empty($_POST['uid'])){
                
                $uid_error = "Username field is empty";
                $error = 4;
            } elseif (!preg_match('/^.{6,16}$/i', $_POST['uid'])){
                $uid_error = "User name must be 6-16 characters";
                $email = $_POST['uid'];
                $error = 4;
            } else {
                // echo 'works';
                $uid = mysqli_real_escape_string($conn, $_POST['uid']);
            }

            $sql = "SELECT * FROM users WHERE user_uid = '$uid' ";
            $result = mysqli_query($conn, $sql);
            $resultCheck = mysqli_num_rows($result);

            if($resultCheck > 0){
                $uid_error = "User name is taken try a different one";
                $email = $_POST['uid'];
                $error = 4;
            }
                    

            //password check


            if(empty($_POST['pwd'])){
                
                $pass_error = "Password field is empty";
                $error = 5;
            } elseif (!preg_match('/^.{6,16}$/i', $_POST['pwd'])){
                $pass_error = "Password must be 6-16 characters";
                $error = 5;
            } elseif ($_POST['pwd'] != $_POST['pwd2']){
                $pass_match_error = "Passwords do not match";
                $error = 5;
            } else {
                // echo 'works';
                $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);
                $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
            }





            
            if ($error == 0) {
                $sql = "INSERT INTO users (user_first, user_last, user_email, user_uid, user_pwd) 
                        VALUES ('$first', '$last', '$email', '$uid', '$hashedPwd');";

                $result = mysqli_query($conn, $sql)
                or die(mysqli_error($conn));
                

                // echo "Your ".$hometype." has been added";
                
                //header("Location: listhomesuccess.php");
                //exit();
                
                // echo "<script text='text/javascript'>window.location.replace('signupsuccess.php')</script>";

                    if(empty($uid) || empty($pwd)){
                            
                            header("Location: index.php?login=empty");
                            exit();
                        } else {
                            $sql = "SELECT * FROM users WHERE user_uid = '$uid' ";
                            $result = mysqli_query($conn, $sql);
                            $resultCheck = mysqli_num_rows($result);
                            if($resultCheck < 1){
                                header("Location: index.php?login=error");
                                exit();

                            } else {
                                if ($row = mysqli_fetch_assoc($result)){
                                    //dehash password
                                    $hashedPwdCheck = password_verify($pwd, $row['user_pwd']);
                                    if($hashedPwdCheck == false) {
                                        header("Location: index.php?login=error");
                                        exit();
                                    } elseif ($hashedPwdCheck == true){
                                        //Log in the user here
                                        $_SESSION['u_id'] = $row['user_id'];
                                        $_SESSION['u_first'] = $row['user_first'];
                                        $_SESSION['u_last'] = $row['user_last'];
                                        $_SESSION['u_email'] = $row['user_email'];
                                        $_SESSION['u_uid'] = $row['user_uid'];
                                        
                                        // header("Location: signupsuccess.php");
                                        // exit();
                                        echo "Your ".$uid." has been added";
    
                
                                        echo "<script text='text/javascript'>window.location.replace('signupsuccess.php')</script>";
                                        
                                    } else {
                                        echo "<p style='color:red; margin-left: 150px;'>";
                                        echo "Error: Could Not Send Data";
                                        echo "<br>".$full_error;
                                        echo "</p>";
                                    }
                                 }
                             }
                        }
                    
                
            } else {
                echo "<p style='color:red; margin-left: 150px;'>";
                echo "Error: Could Not Send Data";
                echo "</p>";
            }
        }
    
        
    



?>

 <p style="color:red; margin-left: 150px;">
        * Required
    </p>
    <section class="addinput">
        <div class="container">
            <h2>Sign Up</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">


                <div><label for="first"><b>First Name</b></label>
                <span style="color:red;">*</span>
                <?php

                    echo '<input type="text" name="first" value="'.$first.'"></div>';
                
                ?>

<?php
    echo "<p style='color:red;'>";
    echo $first_error;
    //echo "<br>";
    echo "</p>";
?>

                <div><label for="last"><b>Last Name</b></label>
                <span style="color:red;">*</span>
                <?php
 
                    echo '<input type="text" name="last" value="'.$last.'"></div>';
                
                ?>

<?php
    echo "<p style='color:red;'>";
    echo $last_error;
    //echo "<br>";
    echo "</p>";
?>


                <div><label for="email"><b>E-mail address</b></label>
                <span style="color:red;">*</span>
                <?php

                    echo '<input type="text" name="email" value="'.$email.'"></div>';
                
                ?>

<?php
    echo "<p style='color:red;'>";
    echo $email_error;
    //echo "<br>";
    echo "</p>";
?>

                <div><label for="uid"><b>Username</b></label>
                <span style="color:red;">*</span>
                <?php

                    echo '<input type="text" name="uid" value="'.$uid.'" placeholder="between 6-16 characters"></div>';
                
                ?>

<?php
    echo "<p style='color:red;'>";
    echo $uid_error;
    //echo "<br>";
    echo "</p>";
?>

                <div><label for="pwd"><b>Password</b></label>                                                        
                <span style="color:red;">*</span>
                <input class="signpass" type = "password" name = "pwd" placeholder="between 6-16 characters"></div>

<?php
    echo "<p style='color:red;'>";
    echo $pass_error;
    //echo "<br>";
    echo "</p>";
?>
                <div><label for="pwd2"><b>Confirm Password</b></label>                                                        
                <span style="color:red;">*</span>
                <input class="signpass" type = "password" name = "pwd2" ></div>

<?php
    echo "<p style='color:red;'>";
    echo $pass_match_error;
    //echo "<br>";
    echo "</p>";
?>
                <input type="submit" value="Sign up">
                </form>
            </div>
        </section>
    </body>
</html>



<?php
    include_once 'footer.php';
?>
        
        
     
