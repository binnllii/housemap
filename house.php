<?php
    session_start();
    
    include('includes/connectiondata.php');
    
    
    function address_builder($address, $city, $state, $zipcode) {
        $full_address = $address.", ".$city.", ".$state." ".$zipcode;
        return $full_address;
    }
    
    $conn = mysqli_connect($server, $user, $pass, $dbname, $port)
    or die('Error connecting to MySQL server.');
    
    $house = $_GET['house_id'];
    
    $query = "SELECT * FROM house INNER JOIN users ON house.user_id=users.user_id WHERE house_id = '$house'";
    
    $result = mysqli_query($conn, $query)
    or die(mysqli_error($conn));
    
    $Address = $Apt = $City = $State = $Zip = $H_Type = $Price = $Bed = $Bath = $Sqft = $First = $Last = $Email = "";
    
    while ($row = mysqli_fetch_array($result)) {
        $Address = $row['address'];
        $Apt = $row['apt_number'];
        $City = $row['city'];
        $State = $row['state'];
        $Zip = $row['zip'];
        $H_Type = $row['home_type'];
        $Price = $row['price'];
        $Bed = $row['bed'];
        $Bath = $row['bath'];
        $Sqft = $row['sqft'];
        $First = ucfirst($row['user_first']);
        $Last = ucfirst($row['user_last']);
        $Email = $row['user_email'];
    }
    
    
    
    $address_info = urlencode(address_builder($Address, $City, $State, $Zip));
    $url = 'http://maps.googleapis.com/maps/api/geocode/json?address='.$address_info.'&sensor=false&components=country:US';
    $geocode = file_get_contents($url);
    $results = json_decode($geocode, true);
    if ($results['status'] == 'OK') {
        $latitude = $results['results'][0]['geometry']['location']['lat'];
        $longitude = $results['results'][0]['geometry']['location']['lng'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link href="index.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin>
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
crossorigin></script>
<style>
#mapid {
height: 360px;
width: 360px;
}
</style>
<title>
HouseMap
</title>
</head>

<body>
<header>
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
<!--                                 <form action = "includes/login-inc.php" method = "POST">
<li class="current"><a href="listing.php">House Listing</a></li>
<li class="current"> <a href="add.php">List a home</a></li>
<li class="current"><a href="signup.php">Sign up</a></li>
<input type="text" name = "uid" placeholder = "Username/e-mail">
<input type="password" name = "pwd" placeholder = "password">
<button type="submit" name = "submit">Login</button>
</form>

<form action = "includes/logout-inc.php" method = "POST">
<li class="current"> <a href="add.php">List a home</a></li>
<li class="current"><a href="listing.php">House Listing</a></li>
<p>You are Logged in</p>
<button type="submit" name="submit">Logout</button>
</form> -->


</ul>
</nav>
</div>
</header>
</html>
</html>


<?php
    echo "<div>";
    echo "<p>Address:<br>";
    echo $Address;
    if (!empty($Apt)) {
        echo ", ".$Apt;
    }
    echo "<br>".$City.", ".$State." ".$Zip;
    echo "</p></div>";
    echo "<div><p>";
    echo "Type of Home: ".$H_Type;
    echo "</p></div>";
    echo "<div><p>";
    echo "Price: $".$Price;
    echo "</p></div>";
    echo "<div> <p>";
    echo "Number of Bedrooms: ".$Bed."<br>";
    echo "Number of Bathrooms: ".$Bath."<br>";
    echo "Property Area: ";
    if (!empty($Sqft)) {
        echo $Sqft."<br>";
    } else {
        echo "Not Provided <br>";
    }
    echo "</p></div>";
    echo "<div><p>";
    echo "Owner: ".$First." ".$Last."<br>";
    echo "Email: <a href='mailto:".$Email."'>".$Email."</a>";
    echo "</p></div>";
    echo "
    <div id='mapid'></div>
    <script>
    var mymap = L.map('mapid').setView([".$latitude.", ".$longitude."], 15);
    var marker = L.marker([".$latitude.", ".$longitude."]).addTo(mymap);
    marker.bindPopup('".$Address."<br>".$City.", ".$State." ".$Zip."').openPopup();
    
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                
                maxZoom: 18,
                id: 'mapbox.streets',
                accessToken: 'pk.eyJ1IjoibGJjaGF2YXJyaWEiLCJhIjoiY2plb2dwdjBnMDBsdzJ3cGk5cnk2OW9sMyJ9.8MnXxMPxPL1SvoPJtlTjPA'
                }).addTo(mymap);

    </script>";
?>

<?php
    include_once 'footer.php';
?>
