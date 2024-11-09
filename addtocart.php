<?php
session_start();

// Initialize the cart session variable if it doesn't exist
if (!isset($_SESSION['cart'])) {
	$_SESSION['cart'] = array();
}

if (isset($_GET['bcid'])) {
	include("includes/connection.php");

	// Sanitize the input
	$book_id = mysqli_real_escape_string($link, $_GET['bcid']);
	$q = "SELECT * FROM book WHERE b_id='$book_id'"; // Use single quotes for the book_id

	// Execute the query
	$res = mysqli_query($link, $q);

	// Check if the query was successful
	if ($res && mysqli_num_rows($res) > 0) {
		$row = mysqli_fetch_assoc($res);

		// Add book to cart
		$_SESSION['cart'][] = array(
			"nm" => $row['b_nm'],
			"img" => $row['b_img'],
			"price" => $row['b_price'],
			"qty" => 1
		);
	} else {
		// Handle case when book is not found or query fails
		$_SESSION['error'] = "Book not found or error in query.";
	}
} elseif (!empty($_POST)) {
	// Update quantities in the cart
	foreach ($_POST as $id => $qty) {
		if (isset($_SESSION['cart'][$id])) {
			$_SESSION['cart'][$id]['qty'] = $qty; // Update quantity only if the book exists in the cart
		}
	}
} elseif (isset($_GET['id'])) {
	// Remove item from the cart
	$id = $_GET['id'];
	unset($_SESSION['cart'][$id]);
}

// Redirect to the cart page
header("location:cart.php");
exit();
