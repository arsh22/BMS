<?php
session_start();

if (!empty($_POST)) {
	// Extract POST data safely
	$unm = $_POST['unm'] ?? '';
	$pwd = $_POST['pwd'] ?? '';
	$_SESSION['error'] = array();

	// Check if the username or password is empty
	if (empty($unm) || empty($pwd)) {
		$_SESSION['error'][] = "Please enter User Name and Password";
		header("Location: login.php");
		exit();
	} else {
		include("includes/connection.php");

		// Prepare the SQL statement
		$stmt = mysqli_prepare($link, "SELECT * FROM register WHERE r_unm = ?");
		mysqli_stmt_bind_param($stmt, 's', $unm); // Bind the username

		// Execute the statement
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt); // Get the result set

		// Fetch the user record
		$row = mysqli_fetch_assoc($result);

		// Check if a user was found and verify password
		if ($row && password_verify($pwd, $row['r_pwd'])) { // Use password_verify for hashed passwords
			// Set session variables for the logged-in user
			$_SESSION['client']['unm'] = $row['r_fnm'];
			$_SESSION['client']['id'] = $row['r_id'];
			$_SESSION['client']['status'] = true;

			// Redirect to the index page
			header("Location: index.php");
			exit();
		} else {
			// Set an error message for incorrect credentials
			$_SESSION['error'][] = "Wrong Username or Password";
			header("Location: login.php");
			exit();
		}

		// Close the statement
		mysqli_stmt_close($stmt);
	}
} else {
	// If POST is empty, redirect to login page
	header("Location: login.php");
	exit();
}
