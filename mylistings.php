<?php
    include_once 'header.php';
?>

<!--     <section class="searchbar">
        <div class="container">
            <h1>Search for your dream house</h1>
            <form action="search.php" method="POST">
            <input type="text" name="address" placeholder = "Enter an address, city, or ZIP code">
            <input type="submit" value="Search">
            </form>
        </div>
    </section> -->




<?php

include('includes/connectiondata.php');


$id = $_SESSION['u_id'];
$address = $_POST['address'];

$address = mysqli_real_escape_string($conn, $address);
// this is a small attempt to avoid SQL injection
// better to use prepared statements


$query = "SELECT * FROM house WHERE user_id = '$id'";


$result = mysqli_query($conn, $query)
or die(mysqli_error($conn));
$queryResult = mysqli_num_rows($result);


if($queryResult < 1){
	echo "No houses are listed yet";
	include_once 'footer.php';
	exit;
}
?>


  <table>
        <thead>
            <tr>
                <th>Address</th>
                <th>City</th>
                <th>State</th>
                <th>Zip Code</th>
                <th>Home Type</th>
                <th>Price</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
            <tbody>



<?php
    while ($row = mysqli_fetch_array($result)) {
        echo '<tr>
        <td>'.$row['address'].'</td>
        <td>'.$row['city'].'</td>
        <td>'.$row['state'].'</td>
        <td>'.$row['zip'].'</td>
        <td>'.$row['home_type'].'</td>
        <td>$'.$row['price'].'</td>
        <td><a href="house.php?house_id='.$row['house_id'].'">View</a></td>
        <td>
        <form action = "includes/delete-inc.php?house_id='.$row['house_id'].'" method = "POST">
        <button onclick="return checkDelete()" name="delete">Delete</button>
        </td>
        </tr>';
    }
?>
            </tbody>
        </table>


<?php
    include_once 'footer.php';
?>