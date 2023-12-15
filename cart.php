<!DOCTYPE html>
<html lang="en">
<body>

<?php
	// include to get some of the user's cookie infomration
	include ('loginSingup.php');
	// maybe make an items database thats going to be terrible tho
	function addToCartDatabase($item, $price) {
		// specifying the connection parameters
		$connString = "mysql:host=localhost;port=8889;dbname=mysql";
		$user = "root";
		$pwd = "root";
		try {
			// creating a php database object
			$pdo = new PDO($connString, $user, $pwd);
			// exception handling parameters
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// creating a table with the user and their items that they want to checkout using session data
			session_start();
			$username = $_SESSION["user"];
			$sql = "INSERT INTO cart SET user='$username', item= '$item', price = '$price'";
			$pdo->exec($sql);

			// closing the connection object
			$pdo = null;
		} catch (PDOException $e) { // exception handling
			echo "Database connection unsuccessful: ";
			die($e->getMessage());
		}
	}


	function removeFromCartDatabase() {
		// specifying the connection parameters
		$connString = "mysql:host=localhost;port=8889;dbname=mysql";
		$user = "root";
		$pwd = "root";
		try {
			// creating a php database object
			$pdo = new PDO($connString, $user, $pwd);
			// exception handling parameters
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// remove the desired item from the cart
			session_start();
			$username = $_SESSION["user"];
			// only want to delete one item
			$sql = "DELETE FROM cart WHERE user='$username'";
			$pdo->exec($sql);

			// closing the connection object
			$pdo = null;
		} catch (PDOException $e) { // exception handling
			echo "Database connection unsuccessful: ";
			die($e->getMessage());
		}
	}

	function addToPaymentInfoDatabase() {
		// specifying the connection parameters
		$connString = "mysql:host=localhost;port=8889;dbname=mysql";
		$user = "root";
		$pwd = "root";
		try {
			// creating a php database object
			$pdo = new PDO($connString, $user, $pwd);
			// exception handling parameters
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// get all the data from cookies or form fields
			session_start();
			$username = $_SESSION["user"];
			$payment = $_GET["payment"];
			$cardNum = $_GET["cardNum"];
			$expire = $_GET["expire"];
			$code = $_GET["code"];
			$fname = $_GET["fname"];
			$lname = $_GET["lname"];
			$city = $_GET["city"];
			$state = $_GET["state"];
			$zip = $_GET["zip"];
			$address = $_GET["address"];
			$phone = $_GET["phone"];

			// adding to paymentinfo table. gets user using cookie data 
			// gets payment info from the submitted form
			$sql = "INSERT INTO paymentinfo SET user='$username', payment='$payment', cardNum='$cardNum', expire='$expire', code='$code',
			fname='$fname', lname='$lname', city='$city', state='$state', zip='$zip', address='$address', phone='$phone'";
			$pdo->exec($sql);

			// closing the connection object
			$pdo = null;
		} catch (PDOException $e) { // exception handling
			echo "Database connection unsuccessful: ";
			die($e->getMessage());
		}
	}

	function setupCart($url) {
		// get current items from url
		$itemsArray = getItemsArray($url);

		// refresh the cart
		removeFromCartDatabase();

		// make new cart with all items in the current url
		foreach ($itemsArray as $item) {
			// add the iitems
			$price = (float)getItemPrice($item);
			addToCartDatabase($item, $price);
		}
	}

	// will add the user's credit card, items ordered, shipping address information to the MySQL database
	function addOrderToDatabase($url) {
		$itemsArray = getItemsArray($url);
		foreach ($itemsArray as $item) {
			$price = (float)getItemPrice($item);

			// specifying the connection parameters
			$connString = "mysql:host=localhost;port=8889;dbname=mysql";
			$user = "root";
			$pwd = "root";
			try {
				// creating a php database object
				$pdo = new PDO($connString, $user, $pwd);
				// exception handling parameters
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				// adding to order table with the user and their items that they want to checkout using cookie data
				session_start();
				$username = $_SESSION["user"];
				$sql = "INSERT INTO orders SET user='$username', item='$item', price='$price'";
				$pdo->exec($sql);

				// closing the connection object
				$pdo = null;
			} catch (PDOException $e) { // exception handling
				echo "Database connection unsuccessful: ";
				die($e->getMessage());
			}
		}
	}

	// gets the price of the passed in item from the database
	function getItemPrice($item) {
		// specifying the connection parameters
		$connString = "mysql:host=localhost;port=8889;dbname=mysql";
		$user = "root";
		$pwd = "root";
		try {
			// creating a php database object
			$pdo = new PDO($connString, $user, $pwd);
			// exception handling parameters
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// get the price from database
			$price = $pdo->query("SELECT price FROM inventory WHERE item='$item'")->fetchColumn();

			// closing the connection object
			$pdo = null;
			return $price;
		} catch (PDOException $e) { // exception handling
			echo "Database connection unsuccessful: ";
			die($e->getMessage());
		}
	}

	// returns an array of the items that the user ordered
	// this information is found in the url
	function getItemsArray($url) {
		// we will fill the array as we go
		$itemsArray = array();

		// get just the query string (url after the ?)
		$queryString = substr($url, strpos($url, "?") + 1);
		
		// move through the query string to get each item
		$currentQueryString = $queryString;

		// do this while there are still items in the currentQueryString
		while (strpos($currentQueryString, "=")) {
			$index = strpos($currentQueryString, "=") + 1;
			$item = '';
			while ($index < strlen($currentQueryString) && $currentQueryString[$index] != '&') {
				$item .= $currentQueryString[$index];
				$index++;
			}
			// add item on to items array
			array_push($itemsArray, $item);

			// make the current query string everything after the equal to see if we have more items
			$currentQueryString = substr($currentQueryString, $index);
		}
		return $itemsArray;
	}

	// makes sure the user has card info in the database, 
	function hasCardInfo($username) {
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
			$databaseUser = $pdo->query("SELECT user FROM paymentinfo WHERE user='$username'")->fetchColumn();

			// closing the connection object
			$pdo = null;
			// return true if there's a user in the database
			return $databaseUser ? true : false;
		} catch (PDOException $e) { // exception handling
			echo "Database connection unsuccessful: ";
			die($e->getMessage());
		}
	}

	// verify that form info matches the info in the database
	// returns an array of the mismatched fields
	function verifyCardInfo($username) {
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

			// make sure all the information that the user passes in matches
			$fields = ["payment", "cardNum", "expire", "code", "fname", "lname", "city", "state", "address", "zip", "phone"];

			$mismatchFields = [];
			foreach ($information as $row) {
				// get all the information from the database
				$index = 0;
				while ($index < count($fields)) {
					$databaseField = $row[$fields[$index]];
					$formField = $_GET[$fields[$index]];

					// make sure database field and entered form match
					if ($databaseField != $formField) {
						$badField = $fields[$index];
						// add the mismatched field to the array
						array_push($mismatchFields, $badField);
					}
					$index++;
				}
			}

			// return array of mismatched fields
			return $mismatchFields;

		} catch (PDOException $e) { // exception handling
			echo "Database connection unsuccessful: ";
			die($e->getMessage());
		}
	}

	// function to make sure user filled out all of the form
	function nonEmptyCheckoutForm(&$paymentErr, &$cardNumErr, &$expireErr, &$codeErr, &$fnameErr, &$lnameErr,
		&$cityErr, &$stateErr, &$addressErr, &$zipErr, &$phoneErr, &$termErr) {

		if (empty($_GET["payment"])) {
			$paymentErr = "payment type is required";
		}
		if (empty($_GET["cardNum"])) {
			$cardNumErr = "card number is required";
		}
		if (empty($_GET["expire"])) {
			$expireErr = "expiration date is required";
		}
		if (empty($_GET["code"])) {
			$codeErr = "code is required";
		}
		if (empty($_GET["fname"])) {
			$fnameErr = "first name is required";
		}
		if (empty($_GET["lname"])) {
			$lnameErr = "last name is required";
		}
		if (empty($_GET["city"])) {
			$cityErr = "city is required";
		}
		if (empty($_GET["state"])) {
			$stateErr = "state is required";
		}
		if (empty($_GET["address"])) {
			$addressErr = "address is required";
		}
		if (empty($_GET["zip"])) {
			$zipErr = "zip code is required";
		}
		if (empty($_GET["phone"])) {
			$phoneErr = "phone number is required";
		}
		if (empty($_GET["accept"])) {
			$termErr = "Please accept the terms";
		}

		// $fields = ["payment", "cardNum", "expire", "code", "fname", "lname", "city", "state", "address", "zip", "phone"];
		return !empty($_GET["payment"]) && !empty($_GET["cardNum"]) && !empty($_GET["expire"]) && !empty($_GET["code"])
		  && !empty($_GET["fname"]) && !empty($_GET["lname"]) && !empty($_GET["city"]) && !empty($_GET["state"])
		  && !empty($_GET["address"]) && !empty($_GET["zip"]) && !empty($_GET["phone"]) && !empty($_GET["accept"]) 
			? true : false;
	}

?>

</body>
</html>
