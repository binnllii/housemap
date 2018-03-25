<?php

$server = "ix.cs.uoregon.edu";
$dbname ="housemap";
$user = "ybl";
$pass = "413290";
$port= "3496";

$conn = mysqli_connect($server, $user, $pass, $dbname, $port)
or die('Error connecting to MySQL server.');


?>