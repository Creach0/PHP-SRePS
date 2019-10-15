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
	<?php echo_nav() ?>

		<h2> Add a Product or Add stock  to a Product </h2>
		<section class="centered">
			<form id="addrecord" method="post" action="addproduct.php">

				<label for="product">Product Name:
				<input type="text" name="product" id="product" /></label><br/>

				<label for="quantity">Quantity/Stock:
				<input type="number" name="quantity" id="quantity" /></label><br/>

				<p>
					<input type="submit" value="Add" />
					<input type="reset" value="Clear" />
				</p>

			</form>



			<?php
				try {
						// Connect to database
						require_once ("settings.php");
						$conn = new mysqli($host,$user,$pwd,$dbnm);
						if ($conn->connect_error) throw new Exception("Failed to connect to database: ".$conn->connect_error);

						echo "<p>Connected to database.</p>";

						$product = $_POST['product'];
						$quantity = $_POST['quantity'];

						//print_r( $_POST );
						//$sql = "INSERT INTO Sales (ProductId, Price, Quantity, Date) VALUES (1, 5, 9, '2019-10-3')";
						$sql = "INSERT INTO Products (ProductName,CategoryId, Stock) VALUES(\"$product\",1, $quantity)";
						//$sql = "INSERT INTO Sales (ProductId, Price, Quantity, Date) VALUES((SELECT ProductId FROM Product WHERE ProductName = '$product'),'$quantity','$price','$date')";
						//$sql = "INSERT INTO Sales (ProductId, Price, Quantity, Date) VALUES (1, 3, 5, '2019-10-3')";
						$result = mysqli_query($conn, $sql);

						//$sql = "SELECT * FROM Sales;";
						//$result = mysqli_query($conn, $sql);
						//$resultCheck = mysqli_num_rows($result);

									// Close everything
							$conn->close();

							} catch(Exception $e) {
									echo "Oops! Something went wrong: ".$e->getMessage();
							}
							?>

		</section>

	</body>
</html>

<!--
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
	<?php echo_nav() ?>

		<h2> Add a Product or Add stock  to a Product </h2>
		<section class="centered">
			<form id="addrecord" method="post" action="addproduct.php">

				<label for="product">Product Name:
				<input type="text" name="product" id="product" /></label><br/>

				<label for="quantity">Quantity/Stock:
				<input type="number" name="quantity" id="quantity" /></label><br/>

				<p>
					<input type="submit" value="Add" />
					<input type="reset" value="Clear" />
				</p>

			</form>



			<?php
				try {
						// Connect to database
						require_once ("settings.php");
						$conn = new mysqli($host,$user,$pwd,$dbnm);
						if ($conn->connect_error) throw new Exception("Failed to connect to database: ".$conn->connect_error);

						echo "<p>Connected to database.</p>";

						$product = $_POST['product'];
						$quantity = $_POST['quantity'];

						//print_r( $_POST );
						//$sql = "INSERT INTO Sales (ProductId, Price, Quantity, Date) VALUES (1, 5, 9, '2019-10-3')";
						$sql = "INSERT INTO Products (ProductName,CategoryId, Stock) VALUES(\"$product\",1, $quantity)";
						//$sql = "INSERT INTO Sales (ProductId, Price, Quantity, Date) VALUES((SELECT ProductId FROM Product WHERE ProductName = '$product'),'$quantity','$price','$date')";
						//$sql = "INSERT INTO Sales (ProductId, Price, Quantity, Date) VALUES (1, 3, 5, '2019-10-3')";
						$result = mysqli_query($conn, $sql);

						//$sql = "SELECT * FROM Sales;";
						//$result = mysqli_query($conn, $sql);
						//$resultCheck = mysqli_num_rows($result);

									// Close everything
							$conn->close();

							} catch(Exception $e) {
									echo "Oops! Something went wrong: ".$e->getMessage();
							}
							?>

		</section>

	</body>
</html> --!>
