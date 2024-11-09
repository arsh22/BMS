<?php
session_start();

if (!empty($_POST)) {
	extract($_POST);
	$_SESSION['error'] = array();

	// Validate Full Name
	if (empty($fnm)) {
		$_SESSION['error']['fnm'] = "Please enter Full Name";
	}

	// Validate Mobile Number
	if (empty($mno)) {
		$_SESSION['error']['mno'] = "Please enter Mobile Number";
	} else {
		if (!is_numeric($mno)) {
			$_SESSION['error']['mno'] = "Please Enter Numeric Mobile Number";
		}
	}

	// Validate Message
	if (empty($msg)) {
		$_SESSION['error']['msg'] = "Please enter Message";
	}

	// Validate Email
	if (empty($email)) {
		$_SESSION['error']['email'] = "Please enter E-Mail ID";
	}

	// Check for errors
	if (!empty($_SESSION['error'])) {
		foreach ($_SESSION['error'] as $er) {
			echo '<font color="red">' . htmlspecialchars($er) . '</font><br>'; // Prevent XSS
		}
	} else {
		// Include database connection
		include("includes/connection.php");

		$t = time(); // Current timestamp

		// Use mysqli_query instead of mysql_query
		$q = "INSERT INTO contact (c_fnm, c_mno, c_email, c_msg, c_time) VALUES ('$fnm', '$mno', '$email', '$msg', '$t')";

		// Execute query and check for errors
		if (mysqli_query($link, $q)) {
			header("Location: contact.php?success=1"); // Optional success flag
			exit();
		} else {
			echo '<font color="red">Error: ' . mysqli_error($link) . '</font>'; // Display error if query fails
		}
	}
} else {
	header("Location: contact.php");
	exit();
}
