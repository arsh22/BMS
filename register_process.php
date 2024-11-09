<?php
session_start();

if (!empty($_POST)) {
	extract($_POST);
	$_SESSION['error'] = array();

	// Full Name validation
	if (empty($fnm)) {
		$_SESSION['error']['fnm'] = "Please enter Full Name";
	}

	// Username validation
	if (empty($unm)) {
		$_SESSION['error']['unm'] = "Please enter User Name";
	}

	// Password validation
	if (empty($pwd) || empty($cpwd)) {
		$_SESSION['error']['pwd'] = "Please enter Password";
	} else if ($pwd != $cpwd) {
		$_SESSION['error']['pwd'] = "Password isn't Match";
	} else if (strlen($pwd) < 8) {
		$_SESSION['error']['pwd'] = "Please Enter Minimum 8 Character Password";
	}

	// Email validation
	if (empty($email)) {
		$_SESSION['error']['email'] = "Please enter E-Mail Address";
	} else if (!preg_match("/^[a-z0-9_]+[a-z0-9_.]*@[a-z0-9_-]+[a-z0-9_.-]*\.[a-z]{2,5}$/", $email)) {
		$_SESSION['error']['email'] = "Please Enter Valid E-Mail Address";
	}

	// Security answer validation
	if (empty($answer)) {
		$_SESSION['error']['answer'] = "Please Enter Security Answer";
	}

	// Contact number validation
	if (empty($cno)) {
		$_SESSION['error']['cno'] = "Please Enter Contact Number";
	} else if (!is_numeric($cno)) {
		$_SESSION['error']['cno'] = "Please Enter Contact Number in Numbers";
	}

	// Check if there are errors
	if (!empty($_SESSION['error'])) {
		header("location:register.php");
		exit();
	} else {
		// No errors, proceed with registration
		include("includes/connection.php");

		// Sanitize input to prevent SQL injection
		$fnm = mysqli_real_escape_string($link, $fnm);
		$unm = mysqli_real_escape_string($link, $unm);
		$pwd = mysqli_real_escape_string($link, $pwd);
		$cno = mysqli_real_escape_string($link, $cno);
		$email = mysqli_real_escape_string($link, $email);
		$question = mysqli_real_escape_string($link, $question);
		$answer = mysqli_real_escape_string($link, $answer);

		$t = time();

		// Insert the data into the database using mysqli_query
		$q = "INSERT INTO register (r_fnm, r_unm, r_pwd, r_cno, r_email, r_question, r_answer, r_time) 
              VALUES ('$fnm', '$unm', '$pwd', '$cno', '$email', '$question', '$answer', '$t')";

		if (mysqli_query($link, $q)) {
			// Registration successful
			header("location:register.php?register=success");
		} else {
			// Error in query execution
			echo "Error: " . mysqli_error($link);
		}
	}
} else {
	// If POST is empty, redirect to register page
	header("location:register.php");
}
