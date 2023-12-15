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

	<?php
	  include 'loginSignup.php';
		
		if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["signup"])) {	
			$usernameErr = "";
			$passwordErr = "";

			// get the entered username and password
			$username = $_GET["username"];
			$password = $_GET["password"];
			// make sure form isn't empty
			if (nonEmptyForm($usernameErr, $passwordErr)) {
				// validate that the user entered a valid username and their username isn't taken
				if (!isInDataBase($_GET["username"])) {
					// if they are valid and non-empty, add them to the database
					addToDatabase($_GET["username"], $_GET["password"]);
					// set cookies
					setCookies();
				}
				else {
					$usernameErr = "username is taken";
				}
			}
		}
	?>

	<main>
		<div class="login-box">
			<form method="GET" id="signupForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
				<p>
					Username: <input name="username" type="text" id="usernameLogin">
					<span class="error"><?php echo $usernameErr;?></span>
				</p>
				<p>
					Password: <input name="password" type="password" id="passwordLogin">
					<span class="error"><?php echo $passwordErr;?></span>
				</p>
				<span><input type="checkbox" name="remember" checked/> Remember me</span>
				<span><input type="submit" name="signup" value="Sign Up" id="submitSignup"></span>
			</form>

			<p>
				Already have an account?
				<a href="login.php">log in</a>
			</p>
		</div>
	</main>

</body>
</html>