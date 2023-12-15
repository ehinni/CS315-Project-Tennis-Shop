<!DOCTYPE html>
<html lang="en">

<?php include 'loginSignup.php';
	// set errors to nothing initially
	$usernameErr = "";
	$passwordErr = "";
	$otherErr = "";
  // see if cookies are set for the username and password. If so, retreive them
	if (isset($_COOKIE["username"])) {
		$username = $_COOKIE["username"];
	}
	else {
		$username = "";
	}

	if (isset($_COOKIE["password"])) {
		$password = $_COOKIE["password"];
	}
	else {
		$password = "";
	}
?>

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
		if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["login"])) {

			// get the entered username and password
			$username = $_GET["username"];
			$password = $_GET["password"];
			
			// validate the form and display errors
			if(nonEmptyForm($usernameErr, $passwordErr)) {
				if (isInDataBase($_GET["username"])) {
					if (validLogin($_GET["username"], $_GET["password"])) {
						// make sure the username is inputted with only valid characters		
						setCookies();
					}
					else {
						$otherErr = "Username and password don't match";
					}
				}
				else {
					$usernameErr = "No such username";
				}
			}
		}
	?>

	<main>
		<div class="login-box">

			<form name="loginForm" id="loginForm" method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
				<p>
					Username: <input name="username" id="usernameLogin" type="text" value="<?php echo $username ?>">
					<span class="error"><?php echo $usernameErr;?></span>
				</p>
				<p>
					Password: <input name="password" id="passwordLogin" type="password" value="<?php echo $password ?>">
					<span class="error"><?php echo $passwordErr;?></span>
				</p>
				<span><input type="checkbox" name="remember" checked/> Remember me</span>
				<span><input type="submit" name="login" value="Login" id="submitLogin"></span>
				<p class="error"><?php echo $otherErr;?></p>
			</form>
			<p>
				Don't have an account?
				<a href="signup.php">sign up</a>
			</p>
	</div>
	</main>

</body>
</html>