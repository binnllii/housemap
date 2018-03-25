<?php
    include_once 'header.php'
?>

    <section class="searchbar">
        <div class="container">
            <h1>Search for your dream house</h1>
            <form action="search.php" method="POST">
            <input type="text" name="address" placeholder = "Enter an address, city, or ZIP code">
            <input type="submit" value="Search">
            </form>
        </div>
    </section>

<?php
    
    include('includes/connectiondata.php');
    
    $conn = mysqli_connect($server, $user, $pass, $dbname, $port)
    or die('Error connecting to MySQL server.');
    
    $query = "SELECT * FROM house INNER JOIN users ON house.user_id=users.user_id";
    
    $result = mysqli_query($conn, $query)
    or die(mysqli_error($conn));
    
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
        </tr>';
    }
?>
            </tbody>
        </table>


<?php
    include_once 'footer.php';
?>
