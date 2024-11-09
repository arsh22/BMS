<?php
include("includes/header.php");
?>

<div id="content">
	<div class="post">
		<h2 class="title"><a href="#"><?php echo htmlspecialchars($_GET['cat']); ?></a></h2> <!-- Use htmlspecialchars to prevent XSS -->
		<p class="meta"></p>
		<div class="entry">

			<?php
			include("includes/connection.php");

			$id = intval($_GET['id']); // Sanitize input to ensure it's an integer

			$blq = "SELECT * FROM book WHERE b_cat = $id"; // Use proper SQL syntax
			$blres = mysqli_query($link, $blq); // Use mysqli_query

			if (!$blres) {
				die("Query failed: " . mysqli_error($link)); // Handle query error
			}

			while ($blrow = mysqli_fetch_assoc($blres)) { // Use mysqli_fetch_assoc
				echo '<div class="book_box">
							<a href="book_detail.php?id=' . $blrow['b_id'] . '">
								<img src="' . htmlspecialchars($blrow['b_img']) . '"> <!-- Use htmlspecialchars to prevent XSS -->
								<h2>' . htmlspecialchars($blrow['b_nm']) . '</h2> <!-- Use htmlspecialchars to prevent XSS -->
								<p>Rs.' . htmlspecialchars($blrow['b_price']) . '</p> <!-- Use htmlspecialchars to prevent XSS -->
							</a>
						</div>';
			}
			?>

			<div style="clear:both;"></div>

		</div>
	</div>
</div><!-- end #content -->

<?php
include("includes/footer.php");
?>