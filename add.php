<?php
    include_once 'header.php';
?>

<?php
    include('includes/connectiondata.php');
    
    $address_error = $apt_error = $city_error = $state_error = 
    $zip_error = $bed_error = $bath_error = $sqft_error = 
    $hometype_error = $price_error = $full_error = "";
    $error = 0;
    //echo $error;
    
    function address_builder($address, $city, $state, $zipcode) {
        $full_address = $address.", ".$city.", ".$state." ".$zipcode;
        return $full_address;
    }
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_SESSION['u_id'])) {
            $user = $_SESSION['u_id'];
            if (empty($_POST['address'])) {
                $address_error = "Address is empty";
                $error = 1;
            } elseif (!preg_match("/^([0-9]+ )[0-9a-zA-Z ]+$/", $_POST['address'])) {
                $address_error = "Invalid Address";
                
                $address = $_POST['address'];
                $error = 1;
            } else {
                $address = mysqli_real_escape_string($conn, $_POST['address']);
            }
            
            //echo $error;
            
            if (!empty($_POST['apt'])) {
                if (!preg_match("/^([a-zA-Z ]+)?([0-9]+)$/", $_POST['apt'])) {
                    $apt_error = "Invalid Apartment Number";
                    $apt = $_POST['apt'];
                    $error = 2;
                } else {
                    $apt = mysqli_real_escape_string($conn, $_POST['apt']);
                }
            }
            
            //echo $error;
            
            if (empty($_POST['city'])) {
                $city_error = "City is empty";
                $error = 3;
            } elseif (!preg_match("/^[a-zA-Z ]+$/", $_POST['city'])) {
                $city_error = "Invalid City";
                $city = $_POST['city'];
                $error = 3;
            } else {
                $city = mysqli_real_escape_string($conn, $_POST['city']);
            }
            
            //echo $error;
            
            if (empty($_POST['state'])) {
                $state_error = "State is empty";
                $error = 4;
            } elseif (!preg_match("/^[A-Z]{2}$/", $_POST['state'])) {
                $state_error = "Invalid State";
                $state = $_POST['state'];
                $error = 4;
            } else {
                $state = mysqli_real_escape_string($conn, $_POST['state']);
            }
            
            //echo $error;
            
            if (empty($_POST['zip'])) {
                $zip_error = "Zipcode is empty";
                $error = 5;
            } elseif (!preg_match("/^[0-9]{5}(?:-[0-9]{4})?$/", $_POST['zip'])) {
                $zip_error = "Invalid Zipcode";
                $zip = $_POST['zip'];
                $error = 5;
            } else {
                $zip = mysqli_real_escape_string($conn, $_POST['zip']);
            }
            
            //echo $error;
            
            
            // Checks if address exists, will implement later
            $address_info = urlencode(address_builder($_POST['address'], $_POST['city'], $_POST['state'],                 $_POST['zip']));
             $url = 'http://maps.googleapis.com/maps/api/geocode/json?address='.$address_info.'&sensor=false&components=country:US';
             $geocode = file_get_contents($url);
             $results = json_decode($geocode, true);
             if ($results['status'] == 'OK') {
             $address = mysqli_real_escape_string($conn, $_POST['address']);
             }
             else {
             $full_error = "Address does not exist. Please check address, city, state, and/or zipcode";
             $error = 1;
             }
             
             //echo $error;
            
            if (empty($_POST['bed'])) {
                $bed_error = "Number of bedrooms is empty";
                $error = 6;
            } elseif (!preg_match("/^[0-9]$/", $_POST['bed'])) {
                $bed_error = "Invalid Number of Bedrooms";
                $bed = $_POST['bed'];
                $error = 6;
            } else {
                $bed = mysqli_real_escape_string($conn, $_POST['bed']);
            }
            
            //echo $error;
            
            if (empty($_POST['bath'])) {
                $bath_error = "Number of bathrooms is empty";
                $error = 7;
            } elseif(!preg_match("/^[0-9]$/", $_POST['bath'])) {
                $bath_error = "Invalid Number of Bathrooms";
                $bath = $_POST['bath'];
                $error = 7;
            } else {
                $bath = mysqli_real_escape_string($conn, $_POST['bath']);
            }
            
            //echo $error;
            
            if (!empty($_POST['sqft'])) {
                if (!preg_match("/^[0-9]+/", $_POST['sqft'])) {
                    $sqft_error = "Invalid Square Feet";
                    $error = 8;
                    $sqft = $_POST['sqft'];
                } else {
                    $sqft = mysqli_real_escape_string($conn, $_POST['sqft']);
                }
            }
            
            //echo $error;
            
            if (empty($_POST['hometype'])) {
                $hometype_error = "Type of house is empty";
                $error = 9;
            } elseif (strcmp(strtolower($_POST['hometype']), "apartment") != 0 && strcmp(strtolower($_POST['hometype']), "house") != 0 && strcmp(strtolower($_POST['hometype']), "condo") != 0 && strcmp(strtolower($_POST['hometype']), "townhouse") != 0) {
                $hometype_error = "Please select a type of home";
                $error = 9;
            } else {
                $hometype = mysqli_real_escape_string($conn, $_POST['hometype']);
            }
            
            //echo $error;
            
            if (empty($_POST['price'])) {
                $price_error = "Price is empty";
                $error = 10;
            } elseif (!preg_match("/^[0-9]+$/", $_POST['price'])) {
                $price_error = "Invalid Price";
                $price = $_POST['price'];
                $error = 10;
            } else {
                $price = mysqli_real_escape_string($conn, $_POST['price']);
            }
            
            //echo $error;
            
            if ($error == 0) {
                $sql = "INSERT INTO house (user_id, address, apt_number, city, state, zip, price, bed, bath, square_feet, home_type) 
                    VALUES ('$user', '$address', '$apt', '$city', '$state', '$zip', '$price', '$bed', '$bath', '$sqft', '$hometype')";
                $result = mysqli_query($conn, $sql)
                or die(mysqli_error($conn));
                
                echo "Your ".$hometype." has been added";
                
                
                echo "<script text='text/javascript'>window.location.replace('listhomesuccess.php')</script>";
                
            } else {
                echo "<p style='color:red; margin-left: 150px;'>";
                echo "Error: Could Not Send Data";
                echo "<br>".$full_error;
                echo "</p>";
            }
        } else {
            echo "<p style='color:red; margin-left: 150px;'>";
            echo "User needs to sign in first in order to add a house";
        }
    }
    
?>
    <p style="color:red; margin-left: 150px;">
        * Required
    </p>
    <section class="addinput">
        <div class="container">
            <h2>Add a Home</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
                <div><label for="address"><b>Address</b></label>
                <span style="color:red;">*</span>
                <!-- <input type="text" name="address" placeholder="Example: 1234 Place Street"></div> -->
                <?php
                    echo '<input type="text" name="address" placeholder="Example: 1234 Place Street" value="'.$address.'"></div>';
                ?>
<?php
    echo "<p style='color:red;'>";
    echo $address_error;
    //echo "<br>";
    echo "</p>";
?>
                <div><label for="apt"><b>Apt &num;</b></label>
                
                <?php
                    echo '<input type="text" name="apt" placeholder="Example: Apt 100" value="'.$apt.'"></div>';
                ?>
<?php
    echo "<p style='color:red;'>";
    echo $apt_error;
    //echo "<br>";
    echo "</p>";
?>
                <div><label for="city"><b>City</b></label>
                <span style="color:red;">*</span>
            
                <?php
                    echo '<input type="text" name="city" value="'.$city.'"></div>';
                ?>
<?php
    echo "<p style='color:red;'>";
    echo $city_error;
    //echo "<br>";
    echo "</p>";
?>
                <div><label for="state"><b>State</b></label>
                <span style="color:red;">*</span>
                <?php

                echo '
                    <select name="state">
                    <option value="">--Select a state--</option>
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iowa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA">Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>

                </select></div>';
            
                ?>
<?php
    echo "<p style='color:red;'>";
    echo $state_error;
    //echo "<br>";
    echo "</p>";
?>
                <div><label for="zip"><b>Zip</b></label>
                <span style="color:red;">*</span>
                <?php
                    echo '<input type="text" name="zip" value="'.$zip.'"></div>';
                ?>

<?php
    echo "<p style='color:red;'>";
    echo $zip_error;
    //echo "<br>";
    echo "</p>";
?>
                <div><label for="bed"><b>Bed &num;</b></label>
                <span style="color:red;">*</span>
                
                <?php
                    echo '<input type="text" name="bed" placeholder = "enter a number from 0-9" value="'.$bed.'"></div>';
                ?>
<?php
    echo "<p style='color:red;'>";
    echo $bed_error;
    //echo "<br>";
    echo "</p>";
?>
                <div><label for="bath"><b>Bath &num;</b></label>
                <span style="color:red;">*</span>
                
                <?php
                    echo '<input type="text" name="bath" placeholder = "enter a number from 0-9" value="'.$bath.'"></div>';
                ?>
<?php
    echo "<p style='color:red;'>";
    echo $bath_error;
    //echo "<br>";
    echo "</p>";
?>
                <div><label for="sqft"><b>Square feet</b></label>

                <?php
                    echo '<input type="text" name="sqft" value="'.$sqft.'"></div>';
                ?>
<?php
    echo "<p style='color:red;'>";
    echo $sqft_error;
    //echo "<br>";
    echo "</p>";
?>
                <div><label for="hometype"><b>Home type</b></label>
                <span style="color:red;">*</span>
                <select name="hometype">
                    <option value="">--Select a home type--</option>
                    <option value="House">House</option>
                    <option value="Apartment">Apartment</option>
                    <option value="Condo">Condo</option>
                    <option value="Townhouse">Townhouse</option>
                </select></div>
<?php
    echo "<p style='color:red;'>";
    echo $hometype_error;
    //echo "<br>";
    echo "</p>";
?>
                <div><label for="price"><b>Price</b></label>
                <span style="color:red;">*</span>

                <?php
                    echo '<input type="text" name="price" value="'.$price.'"></div>';
                ?>
<?php
    echo "<p style='color:red;'>";
    echo $price_error;
    //echo "<br>";
    echo "</p>";
?>
                <input type="submit" value="Insert" name="submit">
                </form>
            </div>
        </section>
    </body>
</html>

<?php
    include_once 'footer.php';
?>
