<?php
    include_once 'header.php';
?>
        
        <section id="showcase">
        	<div class="container">
        		<h1>Welcome to HouseMap</h1>
        		<p>Where you can find everything you need to get the house you want at an affordable price</p>
        	</div>
        </section>
        
        <section class="searchbar">
        	<div class="container">
        		<h1>Search for your dream house</h1>
        		<form action="search.php" method="POST">
        		<input type="text" name="address" placeholder = "Enter an address, city, or ZIP code">
        		<input type="submit" value="Search">
        		</form>
        	</div>
        	
        </section>
        
        <section id="boxes">
        	<div class="container">
         		<div class="box">
         			<img src="./image/guiter.jpg">
         			<h3>Sell your House</h3>
         			<p>Want to sell your house or apartment? List is now!</p>
         		</div>
         		<div class="box">
         			<img src="./image/lap.jpg">
         			<h3>Buy a new house</h3>
         			<p>Time to star your new life with a new house!</p>
         		</div>
         		<div class="box">
         			<img src="./image/type.jpg">
         			<h3>Move somewhere</h3>
         			<p>Have problem to find a new place? We can help!</p>
         		</div>
         	</div>
         </section>
         

<?php
    include_once 'footer.php';
?>
        