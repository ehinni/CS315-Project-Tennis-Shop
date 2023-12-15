<!DOCTYPE html>
<html lang="en">
<body>

<?php
	function setCookies() {

		// set cookies for username and password if the user hits remember me
		if(!empty($_GET["remember"])) {
			setcookie ("username",$_GET["username"],time()+ (86400 * 30));
			setcookie ("password",$_GET["password"],time()+ (86400 * 30));
		}
		else {
			// remove the cookies if the user doesn't want to be remembered
			setcookie("username", "", time() - 3600);
			setcookie("password", "", time() - 3600);
		}

		// start the session for this user
		session_start();

		// create a session id for the logged in user and store it in session
		$_SESSION['sid'] = session_id();
		$_SESSION['user'] = $_GET["username"];

		// redirect the user back to the home page of the website
		header('Location: /home.html');
	}

	// function to make sure user filled out all of the form
	function nonEmptyForm(&$usernameErr, &$passwordErr) {
		if (empty($_GET["username"])) {
			$usernameErr = "username is required";
		}
		if (empty($_GET["password"])) {
			$passwordErr = "password is required";
		}

		return !empty($_GET["username"]) && !empty($_GET["password"]) ? true : false;
	}

	// adds the username and password to the database
	function addToDatabase($username, $password) {
		// specifying the connection parameters
		$connString = "mysql:host=localhost;port=8889;dbname=mysql";
		$user = "root";
		$pwd = "root";
		try {
			// creating a php database object
			$pdo = new PDO($connString, $user, $pwd);
			// exception handling parameters
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// creating the query to get rows of data from the table
			$sql = "INSERT INTO users SET username= '$username', password = '$password'";
			$pdo->exec($sql);

			// closing the connection object
			$pdo = null;
		} catch (PDOException $e) { // exception handling
			echo "Database connection unsuccessful: ";
			die($e->getMessage());
		}
	}

	// checks if username is in the database yet or not
	function isInDataBase($username) {
		// specifying the connection parameters
		$connString = "mysql:host=localhost;port=8889;dbname=mysql";
		$user = "root";
		$pwd = "root";
		try {
			// creating a php database object
			$pdo = new PDO($connString, $user, $pwd);
			// exception handling parameters
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
			// assigns the number of rows that contain the passed in username
			$count = $pdo->query("SELECT count(*) from users WHERE username='$username'")->fetchColumn(); 

			// closing the connection object
			$pdo = null;

			// return true if there is no username in the database that matches the passed in username
			return $count > 0 ? true : false;

		} catch (PDOException $e) { // exception handling
			echo "Database connection unsuccessful: ";
			die($e->getMessage());
		}
	}

	// checks if password matches the username in the database
	function validLogin($username, $password) {
			// specifying the connection parameters
			$connString = "mysql:host=localhost;port=8889;dbname=mysql";
			$user = "root";
			$pwd = "root";
			try {
				// creating a php database object
				$pdo = new PDO($connString, $user, $pwd);
				// exception handling parameters
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
				// assigns the number of rows that contain the passed in username
				$queryPassword = $pdo->query("SELECT password from users WHERE username='$username'")->fetchColumn();
	
				// closing the connection object
				$pdo = null;
	
				// return true if there is no username in the database that matches the passed in username
				return $queryPassword == $password ? true : false;
	
			} catch (PDOException $e) { // exception handling
				echo "Database connection unsuccessful: ";
				die($e->getMessage());
			}
	}

?>

</body>
</html>
