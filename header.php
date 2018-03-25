<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link href="index.css" rel="stylesheet">
        <meta charset="utf-8">
        <title>
            HouseMap
        </title>

        <script language="JavaScript" type="text/javascript">
        function checkDelete(){
            return confirm('Are you sure you want to delete this home?');
        }
        </script>
    </head>
    
    <body>
        <header>
            <meta charset="utf-8">
             <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>jQuery UI Dialog - Default functionality</title>
            <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
            <link rel="stylesheet" href="/resources/demos/style.css">
            <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


            </script>

            <div class="container">
            	<div class="logo-left">
                <a href="index.php"><img src="houselogo.jpg" alt="Home" width="320" height="90">
                </div>
                <nav>                    
                	<ul>
                        <?php
                            if(isset($_SESSION['u_id'])){

                                
                                echo '<form action = "includes/logout-inc.php" method = "POST">
                                    <li class="current"><a href="listing.php">House Listing</a></li>
                                    <li class="current"> <a href="add.php">List a home</a></li>  
                                    <li class="current"><a href="mylistings.php">My Listings</a></li>               
                                    <li>You are Logged in as '.$_SESSION['u_uid'].'</li>
                                    <button type="submit" name="submit">Logout</button>
                                    </form>';
                            } else{
                                echo ' <form action = "includes/login-inc.php" method = "POST">
                                <li class="current"><a href="listing.php">House Listing</a></li>
                                <li class="current"> <a href="add.php">List a home</a></li>
                                <li class="current"><a href="signup.php">Sign up</a></li>
                                <input type="text" name = "uid" placeholder = "Username/e-mail">
                                <input type="password" name = "pwd" placeholder = "password">
                                <button type="submit" name = "submit">Login</button>

                                
                                </form>';

                                
                            }

                        ?>

                        <?php
                            $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                            if(strpos($fullUrl, "login=empty") == true){
                                echo "<script>alert('Login fields are empty');</script>";
                            }
                            if(strpos($fullUrl, "login=noid") == true){
                                echo "<script>alert('Invalid ID');</script>";
                            }
                            if(strpos($fullUrl, "login=nomatch") == true){
                                echo "<script>alert('Username and Password does not match');</script>";
                            }
                        ?>

                                

                    
                    </ul>
                </nav>            
            </div>

        </header>
</html>
</html>
