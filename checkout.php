<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title>Checkout</title>
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

	<?php
		include 'cart.php';

		// url that contains the items
		$url = $_SERVER['HTTP_REFERER'];
		// setup the shopping cart

		setupCart($url);

		// set errors to nothing initially
		$paymentErr = "";
		$cardNumErr = "";
		$expireErr = "";
		$codeErr = "";
		$fnameErr = "";
		$lnameErr = "";
		$cityErr = "";
		$stateErr = "";
		$addressErr = "";
		$zipErr = "";
		$phoneErr = "";
		$termErr = "";


		if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["checkout"])) {
			$url = $_SERVER['HTTP_REFERER']; // the entire url for items

			session_start();
			$user = $_SESSION['user'];

			// make sure form isn't empty
			if (nonEmptyCheckoutForm($paymentErr, $cardNumErr, $expireErr, $codeErr, $fnameErr, $lnameErr, $cityErr,
				$stateErr, $addressErr, $zipErr, $phoneErr, $termErr)) {

				if (hasCardInfo($user)) {
					$mismatchedFields = verifyCardInfo($user);
					if (count($mismatchedFields) == 0) {
						// info has been verified, so put order in
						addOrderToDatabase($url);
					}
					else {
						// set errors to all mismatched fields
						foreach($mismatchedFields as $field) {
							// set the errors to the proper form elements
							if ($field == "payment") {
								$paymentErr = "$field does not match database";
							}
							if ($field == "cardNum") {
								$cardNumErr = "card number does not match database";
							}
							if ($field == "expire") {
								$expireErr = "exiration date does not match database";
							}
							if ($field == "code") {
								$codeErr = "$field does not match database";
							}
							if ($field == "fname") {
								$fnameErr = "first name does not match database";
							}
							if ($field == "lname") {
								$lnameErr = "last name does not match database";
							}
							if ($field == "city") {
								$cityErr = "$field does not match database";
							}
							if ($field == "state") {
								$stateErr = "$field does not match database";
							}
							if ($field == "address") {
								$addressErr = "$field does not match database";
							}
							if ($field == "zip") {
								$zipErr = "$field code does not match database";
							}
							if ($field == "phone") {
								$phoneErr = "$field number does not match database";
							}
						}
					}
				}
				else {
					// if the user has yet to shop, add their info and order
					addToPaymentInfoDatabase();
					addOrderToDatabase($url);
				}
			}
		}
	?>

	<main>
		<div class="center">
			<h1>Checkout</h1>
		</div>
		
		<article class="split-grid">
			
			<section>
				<h2>Please enter your information before you checkout</h2>

				<!-- protect from script injections -->
				<form name="paymentForm" method="GET" id="paymentForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
					<h3>Payment Method</h3>
					<div class="form-grid">
						<label for="payment">Type of Payment:</label>
						<select name="payment" id="payment">
							<option value="visa">Visa</option>
							<option value="masterCard">MasterCard</option>
							<option value="americanExpress">American Express</option>
							<option value="discover">Discover</option>
						</select>
						<span class="error"><?php echo $paymentErr;?></span>

						<label for="cardNum">Card Number:</label>
						<input type="text" id="cardNum" name="cardNum">
						<span class="error"><?php echo $cardNumErr;?></span>

						<label for="expire">Expiration Date:</label>
						<input type="text" id="expire" name="expire" placeholder="MM/YY">
						<span class="error"><?php echo $expireErr;?></span>

						<label for="code">Security Code:</label>
						<input type="text" id="code" name="code">
						<span class="error"><?php echo $codeErr;?></span>

					</div>


					<h3>Billing Infromation</h3>
					<div class="form-grid">
						<label for="fname">First name:</label>
						<input type="text" id="fname" name="fname">
						<span class="error"><?php echo $fnameErr;?></span>

						<label for="lname">Last name:</label>
						<input type="text" id="lname" name="lname">
						<span class="error"><?php echo $lnameErr;?></span>

						
						<label for="city">City:</label>
						<input type="text" id="city" name="city">
						<span class="error"><?php echo $cityErr;?></span>
						<label for="state">State:</label>
						<input type="text" id="state" name="state">
						<span class="error"><?php echo $stateErr;?></span>
						<label for="zip">Zip Code:</label>
						<input type="text" id="zip" name="zip">
						<span class="error"><?php echo $zipErr;?></span>
						
						
						<label for="address">Billing Address:</label>
						<input type="text" id="address" name="address">
						<span class="error"><?php echo $addressErr;?></span>
						<label for="phone">Phone Number:</label>
						<input type="text" id="phone" name="phone" placeholder="XXX-XXX-XXXX">
						<span class="error"><?php echo $phoneErr;?></span>

					</div>
					<label>I accept the terms and conditions of this website:</label>
					<input type="checkbox" id="accept" name="accept">
					<span class="error"><?php echo $termErr;?></span>
					
					<br><br>
					<input class="fancy-button" name="checkout" id="submit" type="submit" value="Checkout">
					<input class="fancy-button" type="reset" value="Clear Form">
				</form>
				<button class="fancy-button-double" id="clearCart">Clear Cart</button>
			</section>
			
			<section>
				<table 
					id="orderTable">
				</table>
			</section>

			<section>
				<p>Submitted JSON will display below when checkout is successful</p>
				<p id="textJSON"></p>
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

</body>
</html>
