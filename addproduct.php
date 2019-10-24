<?php
	session_start();
	include_once("common.php");
?>
<!DOCTYPE html>
<html lang="en">
	<?php echo_head(); ?>
	<head>
		<script src="scripts/AddProductValidateInput.js"></script>
	</head>
	<body>
		<header>
			<h1>Sales Records</h1>
		</header>
	<?php echo_nav() ?>

		<h2> Add a record </h2>
		<section class="centered">
			<form id="addrecord" method="post" action="addproduct.php">

				<label for="product">Product Name:
				<input type="text" name="product" id="product" /></label><br/>

				<label for="quantity">Quantity/Stock:
				<input type="number" name="quantity" id="quantity" /></label><br/>

				<input type="hidden" id="inputValid" name="inputValid"/>

				<p>
					<input type="submit" value="Add" onclick="return validateInput()" />
					<input type="reset" value="Clear" />
				</p>

			</form>



			<?php
				if ($_POST['inputValid'] == "true")
				{
					try {
							// Connect to database
							require_once ("settings.php");
							$conn = new mysqli($host,$user,$pwd,$dbnm);
							if ($conn->connect_error) throw new Exception("Failed to connect to database: ".$conn->connect_error);

							echo "<p>Connected to database.</p>";

							$product = $_POST['product'];
							$quantity = $_POST['quantity'];

							$sql = "INSERT INTO Products (ProductName,CategoryId, Stock) VALUES($product,1, $quantity)";
							$result = mysqli_query($conn, $sql);

							// Close everything
							$conn->close();

						} catch(Exception $e) {
								echo "Oops! Something went wrong: ".$e->getMessage();
						}
				}
			?>

		</section>

	</body>
</html>
