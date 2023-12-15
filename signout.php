<!DOCTYPE html>
<html lang="en">
<body>

<?php
	session_start();
	// remove session variables
	session_unset();
	// destroy the current session
	session_destroy();

	// direct user back to the home page
	header('Location: /index.html');
?>

</body>
</html>
