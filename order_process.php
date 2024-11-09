<?php
session_start();
include("includes/connection.php");

if (!empty($_POST)) {
	extract($_POST);
	$_SESSION['error'] = array();

	// Validate inputs
	if (empty($fnm)) {
		$_SESSION['error'][] = "Enter Full Name";
	}

	if (empty($add)) {
		$_SESSION['error'][] = "Enter Full Address";
	}

	if (empty($pc)) {
		$_SESSION['error'][] = "Enter City Pincode";
	}

	if (empty($city)) {
		$_SESSION['error'][] = "Enter City";
	}

	if (empty($state)) {
		$_SESSION['error'][] = "Enter State";
	}

	if (empty($mno)) {
		$_SESSION['error'][] = "Enter Mobile Number";
	} else if (!is_numeric($mno)) {
		$_SESSION['error'][] = "Enter Mobile Number in Numbers";
	}

	// Redirect if there are errors
	if (!empty($_SESSION['error'])) {
		header("location:order.php");
		exit();
	} else {
		$rid = $_SESSION['client']['id'];

		// Sanitize inputs
		$fnm = mysqli_real_escape_string($link, $fnm);
		$add = mysqli_real_escape_string($link, $add);
		$pc = mysqli_real_escape_string($link, $pc);
		$city = mysqli_real_escape_string($link, $city);
		$state = mysqli_real_escape_string($link, $state);
		$mno = mysqli_real_escape_string($link, $mno);

		// Prepare the insert query
		$q = "INSERT INTO `bms`.`orders` (
                `o_name`, 
                `o_address`, 
                `o_pincode`, 
                `o_city`, 
                `o_state`, 
                `o_mobile`, 
                `o_rid`
              ) VALUES (
                '$fnm', 
                '$add', 
                '$pc', 
                '$city', 
                '$state', 
                '$mno', 
                '$rid'
              )";

		// Execute the query
		if (mysqli_query($link, $q)) {
			header("location:order.php?order=success");
		} else {
			$_SESSION['error'][] = "Error placing order: " . mysqli_error($link);
			header("location:order.php");
		}
	}
} else {
	header("location:order.php");
}
exit();
