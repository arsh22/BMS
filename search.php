<?php
include("includes/header.php");
?>

<div id="content">
	<div class="post">
		<h2 class="title"><a href="#">Search: <?php echo htmlspecialchars($_GET['s']); ?></a></h2> <!-- Use htmlspecialchars to prevent XSS -->
		<p class="meta"></p>
		<div class="entry">

			<?php
			include("includes/connection.php");

			$s = mysqli_real_escape_string($link, $_GET['s']); // Sanitize input

			// First, find the category ID that matches the search term
			$cat_query = "SELECT cat_id FROM category WHERE cat_nm LIKE '%$s%'";
			$cat_res = mysqli_query($link, $cat_query); // Execute the category query

			if (!$cat_res) {
				die("Category query failed: " . mysqli_error($link)); // Handle query error
			}

			// Prepare an array to hold the category IDs
			$cat_ids = [];
			while ($cat_row = mysqli_fetch_assoc($cat_res)) {
				$cat_ids[] = $cat_row['cat_id'];
			}

			// If we found category IDs, use them to search for books
			if (!empty($cat_ids)) {
				// Prepare the category IDs for the SQL query
				$cat_ids_list = implode(",", $cat_ids);
				$blq = "SELECT * FROM book WHERE b_cat IN ($cat_ids_list)"; // Use IN clause for multiple categories
			} else {
				// If no categories were found, just search by book name
				$blq = "SELECT * FROM book WHERE b_nm LIKE '%$s%'"; // Search by book name
			}

			$blres = mysqli_query($link, $blq); // Execute the book query

			if (!$blres) {
				die("Book query failed: " . mysqli_error($link)); // Handle query error
			}

			// Display books based on the search results
			if (mysqli_num_rows($blres) > 0) {
				while ($blrow = mysqli_fetch_assoc($blres)) {
					echo '<div class="book_box">
                            <a href="book_detail.php?id=' . $blrow['b_id'] . '">
                                <img src="' . htmlspecialchars($blrow['b_img']) . '"> <!-- Use htmlspecialchars to prevent XSS -->
                                <h2>' . htmlspecialchars($blrow['b_nm']) . '</h2> <!-- Use htmlspecialchars to prevent XSS -->
                                <p>Rs. ' . htmlspecialchars($blrow['b_price']) . '</p> <!-- Use htmlspecialchars to prevent XSS -->
                            </a>
                          </div>';
				}
			} else {
				echo '<p>No books found for your search.</p>'; // Handle case where no books are found
			}
			?>

			<div style="clear:both;"></div>

		</div>
	</div>
</div><!-- end #content -->

<?php
include("includes/footer.php");
?>