<!-- put order history and stuff here i guess -->
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title>Tennis Bags</title>
	<link rel="stylesheet" href="mobile.css" media="screen and (max-width:480px)"/>
	<link rel="stylesheet" href="tablet.css" media="screen and (min-width:481px) and (max-width:768px)"/>
	<link rel="stylesheet" href="desktop.css" media="screen and (min-width:769px)"/>
	<script type="text/javascript" src="tennisShop.js"></script>
</head>

<body>
	<header>
		<nav>
			<a href="home.html"><img src="tennisBallLogo.png" alt="Tennis Ball Logo"></a>
			<a href="deals.html">Deals</a>
			<a href="racquets.html">Racquets</a>
			<a href="balls.html">Balls</a>
			<a href="bags.html">Bags</a>
			<a href="shoes.html">Shoes</a>
			<a href="memoribilia.html">Autographed Memorabilia</a>
			<a href="giveAway.html">Giveaway</a>
			<a href="inventory.html">Store Inventory</a>
			<a href="checkout.php"><img src="shoppingCart.png" alt="Shopping Cart Logo"></a>
			<a href="accountinfo.php">My Account</a>
			<a href="signout.php">Sign Out</a>
		</nav>
	</header>

	<main>
		<div class="center">
			<h1>Account Information for 
				<?php 
					session_start();
					$username = $_SESSION["user"];
					echo "$username";
				?>
			</h1>
		</div>
		
		<article class="grid-container-half">
			<section>
				<h2>General Information</h2>

				<?php
					include 'loginSignup.php'; // for cookie and session information
					include 'cart.php'; // we need this for previous orders and other general info
					
					// get session data
					session_start();
					$session = $_SESSION["sid"];
					$username = $_SESSION["user"];
					echo "<p>Username: $username</p>";
					
					// get general account info of the user based on their cookies
					$information = getAccountInfo($username);
					// go through and get all of the info from the returned query
					foreach($information as $row) {
						$payment = $row["payment"];
						$fname = $row["fname"];
						$lname = $row["lname"];
						$city = $row["city"];
						$state = $row["state"];
						$address = $row["address"];
						$phone = $row["phone"];
						echo "
						<p>First Name: $fname</p>
						<p>Last Name: $lname</p>
						<p>Home Town: $city, $state</p>
						<p>Address: $address</p>
						<p>Phone Number: $phone</p>
						";
					}
					echo "<p>Session: $session</p>";
				?>

			</section>
			<section>
				<h2>Order History</h2>
				<?php
				  $orderInfo = getOrderInfo($username);

					// print out every order that the user made
					foreach($orderInfo as $order) {
						$item = $order["item"];
						$price = $order["price"];
						echo "<p>$item: $$price</p>";
					}
				?>

			</section>

			<section>
				<?php
					echo "<h2>Special Member Price Deal for $username:<h2>";
				?>
			</section>

			<section>
				<?php
					// will only get a new item if the user hasn't gotten one already
					if (!hasGottenDeal($username)) {
						// we will give the user a special discount for 1 of 3 items
						$randomItemIndex = rand(1,3);

						// display containers based on what the random number is
						if ($randomItemIndex == 1) {
							echo "
								<figure>
									<img src='headRacquet.jpeg' alt='Head Racquet' />
									<figcaption><small>Discounted Head Speed Pro | $180</small></figcaption>
									<button class='addToCartButton' name='Discount Head Speed Pro|180'>Add to Cart</button>
								</figure>
							";
							insertMemberPrice($username, 'discount-head-speed-pro');
						}
						else if ($randomItemIndex == 2) {
							echo "
								<figure class='add-margin'>
									<img src='babRacquet.jpeg' alt='Babolat Racquet' />
									<figcaption><small>Discounted Babolat Pure Aero Rafa | $200</small></figcaption>
									<button class='addToCartButton' name='Discount Babolat Pure Aero Rafa|200'>Add to Cart</button>
								</figure>
							";
							insertMemberPrice($username, 'discount-babolat-pure-aero-rafa');
						}
						else {
							echo "
								<figure>
									<img src='wilsonRacquet.jpeg' alt='Wilson Racquet' />
									<figcaption><small>Discounted Wilson Ultra | $195</small></figcaption>
									<button class='addToCartButton' name='Discount Wilson Ultra|195'>Add to Cart</button>
								</figure>
							";
							insertMemberPrice($username, 'discount-wilson-ultra');
						}
					}
					else {
						$item = getDiscountedItem($username);
						if ($item == 'discount-head-speed-pro') {
							echo "
								<figure>
									<img src='headRacquet.jpeg' alt='Head Racquet' />
									<figcaption><small>Discounted Head Speed Pro | $180</small></figcaption>
									<button class='addToCartButton' name='Discount Head Speed Pro|180'>Add to Cart</button>
								</figure>
							";
						}
						else if ($item == 'discount-babolat-pure-aero-rafa') {
							echo "
								<figure class='add-margin'>
									<img src='babRacquet.jpeg' alt='Babolat Racquet' />
									<figcaption><small>Discounted Babolat Pure Aero Rafa | $200</small></figcaption>
									<button class='addToCartButton' name='Discount Babolat Pure Aero Rafa|200'>Add to Cart</button>
								</figure>
							";
						}
						else {
							echo "
								<figure>
									<img src='wilsonRacquet.jpeg' alt='Wilson Racquet' />
									<figcaption><small>Discounted Wilson Ultra | $195</small></figcaption>
									<button class='addToCartButton' name='Discount Wilson Ultra|195'>Add to Cart</button>
								</figure>
							";
						}
					}
				?>
			</section>
		</article>
		
	</main>
	
	<footer>
		<br>
		<nav>
			<a href="home.html"><img src="tennisBallLogo.png" alt="Tennis Ball Logo"></a>
			<a href="deals.html">Deals</a>
			<a href="racquets.html">Racquets</a>
			<a href="balls.html">Balls</a>
			<a href="bags.html">Bags</a>
			<a href="shoes.html">Shoes</a>
			<a href="memoribilia.html">Autographed Memorabilia</a>
			<a href="giveAway.html">Giveaway</a>
			<a href="inventory.html">Store Inventory</a>
			<a href="checkout.php"><img src="shoppingCart.png" alt="Shopping Cart Logo"></a>
			<a href="accountinfo.php">My Account</a>
			<a href="signout.php">Sign Out</a>
		</nav>
	</footer>

	<?php
		function getAccountInfo($username) {
			// specifying the connection parameters
			$connString = "mysql:host=localhost;port=8889;dbname=mysql";
			$user = "root";
			$pwd = "root";
			try {
				// creating a php database object
				$pdo = new PDO($connString, $user, $pwd);
				// exception handling parameters
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				// get the information from database
				$information = $pdo->query("SELECT * FROM paymentinfo WHERE user='$username'")->fetchAll();

				// closing the connection object
				$pdo = null;
				return $information;
			} catch (PDOException $e) { // exception handling
				echo "Database connection unsuccessful: ";
				die($e->getMessage());
			}
		}

		function getOrderInfo($username) {
			$connString = "mysql:host=localhost;port=8889;dbname=mysql";
			$user = "root";
			$pwd = "root";
			try {
				// creating a php database object
				$pdo = new PDO($connString, $user, $pwd);
				// exception handling parameters
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				// get the order information from database
				$orderInformation = $pdo->query("SELECT * FROM orders WHERE user='$username'")->fetchAll();

				// closing the connection object
				$pdo = null;
				return $orderInformation;
			} catch (PDOException $e) { // exception handling
				echo "Database connection unsuccessful: ";
				die($e->getMessage());
			}
		}

		function insertMemberPrice($username, $discountItem) {
				// get the discounted price
				$discountPrice = (float)getItemPrice($discountItem);
	
				// specifying the connection parameters
				$connString = "mysql:host=localhost;port=8889;dbname=mysql";
				$user = "root";
				$pwd = "root";
				try {
					// creating a php database object
					$pdo = new PDO($connString, $user, $pwd);
					// exception handling parameters
					$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
					// adding to deals table with the user and their items that they want to checkout using cookie data
					session_start();
					$username = $_SESSION["user"];
					$sql = "INSERT INTO `member-deals` SET user='$username', discount_item='$discountItem', discount_price='$discountPrice'";
					$pdo->exec($sql);
	
					// closing the connection object
					$pdo = null;
				} catch (PDOException $e) { // exception handling
					echo "Database connection unsuccessful: ";
					die($e->getMessage());
				}
		}
		function hasGottenDeal($username) {
			// specifying the connection parameters
			$connString = "mysql:host=localhost;port=8889;dbname=mysql";
			$user = "root";
			$pwd = "root";
			try {
				// creating a php database object
				$pdo = new PDO($connString, $user, $pwd);
				// exception handling parameters
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				// get the user from database
				$databaseUser = $pdo->query("SELECT `user` FROM `member-deals` WHERE user='$username'")->fetchColumn();

				// closing the connection object
				$pdo = null;

				// returns true if there is a matching user in the database
				return $databaseUser ? true : false;
			} catch (PDOException $e) { // exception handling
				echo "Database connection unsuccessful: ";
				die($e->getMessage());
			}
		}

		function getDiscountedItem($username) {
			// specifying the connection parameters
			$connString = "mysql:host=localhost;port=8889;dbname=mysql";
			$user = "root";
			$pwd = "root";
			try {
				// creating a php database object
				$pdo = new PDO($connString, $user, $pwd);
				// exception handling parameters
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				// get the discounted item from database
				$discountedItem = $pdo->query("SELECT discount_item FROM `member-deals` WHERE user='$username'")->fetchColumn();

				// closing the connection object
				$pdo = null;

				// returns true if there is a matching user in the database
				return $discountedItem;
			} catch (PDOException $e) { // exception handling
				echo "Database connection unsuccessful: ";
				die($e->getMessage());
			}
		}
	?>
	
</body>
</html>