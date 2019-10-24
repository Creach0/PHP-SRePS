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

		<h2> Add a record </h2>
		<section class="centered">
			<form id="addrecord" method="post" action="addrecord.php">

				<label for="product">Product:
				<input type="text" name="product" id="product" /></label><br/>

				<label for="price">Price:
				<input type="number" step=".01" name="price" id="price" /></label><br/>

				<label for="quantity">Quantity:
				<input type="number" name="quantity" id="quantity" /></label><br/>

				<label for="date">Date:
				<input type="date" name="date" id="date" /></label><br/>

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
						$price = $_POST['price'];


						$date_in_seconds = strtotime($_POST['date']);
						$date = date('Y-m-d', $date_in_seconds);

						//print_r( $_POST );
						//$sql = "INSERT INTO Sales (ProductId, Price, Quantity, Date) VALUES (1, 5, 9, '2019-10-3')";
						$sql = "INSERT INTO Sales (ProductId, Price, Quantity, Date) VALUES((SELECT ProductId FROM Products WHERE ProductName = \"$product\"),\"$price\",\"$quantity\",\"$date\")";
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
            <div id="stockAlert"></div>

		</section>

	</body>
</html>
