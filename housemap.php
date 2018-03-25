<?php

include('includes/connectiondata.php');




  
$address = $_POST['address'];

$address = mysqli_real_escape_string($conn, $address);
// this is a small attempt to avoid SQL injection
// better to use prepared statements

$query = "SELECT h.address, h.apt_number, h.city, h.state, h.zip
			FROM housemap.house h 
			WHERE address like  ";
$query = $query. "'%$address%'";

$result = mysqli_query($conn, $query)
or die(mysqli_error($conn));


print "<pre>";
while($row = mysqli_fetch_array($result, MYSQLI_BOTH))
  {
    print "\n";
    print "$row[address] $row[apt_number] $row[city] $row[state] $row[zip]";
  }
print "</pre>";

mysqli_free_result($result);

mysqli_close($conn);

?>

