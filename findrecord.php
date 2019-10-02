<?php
	session_start();
	include_once("common.php");
?>
<!DOCTYPE html>
<html lang="en">
	<?php echo_head(); ?>
	<body>
		<header>
			<h1>Sales Records</h1>
		</header>

		<?php echo_nav(); ?>

		<section class="centered">
			<form id="findrecord" method="post" action="viewrecord.php">

				<label for="product">Product:</label>
				<input type="text" name="product" id="product" /><br />

				<label for="quantity">Quantity:</label>
				<input type="number" name="quantity" id="quantity" /><br />

				<label for="price">Price:</label>
				<input type="number" name="price" id="price" /><br />

				<label for="date">Date:</label>
				<input type="date" name="date" id="date" /><br />

				<p>
					<input type="submit" value="Search" />
					<input type="reset" value="Clear" />
				</p>

			</form>
		</section>

	</body>
</html>
