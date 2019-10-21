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

						$sql = "INSERT INTO Sales (ProductId, Price, Quantity, Date) VALUES((SELECT ProductId FROM Products WHERE ProductName = \"$product\"),\"$price\",\"$quantity\",\"$date\")";
						$result = mysqli_query($conn, $sql);

						//Subtract from existing stock
						//Quantity in stock = quantity in stock - quantity sold WHERE Product = product
						$sql = "UPDATE Products SET Stock = Stock - \"$quantity\" WHERE ProductName = \"$product\"";
						$result = mysqli_query($conn, $sql);

						// Close everything
						$conn->close();

				} catch(Exception $e) {
					echo "Oops! Something went wrong: ".$e->getMessage();
				}
			?>

		</section>

	</body>
</html>
