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
				<input type="number" name="price" id="price" /></label><br/>

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

			 ?>

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
						$date = $_POST['date'];

						$sql = "INSERT INTO 'Sales' (ProductId, Price, Quantity, Date) VALUES('$product','$quantity','$price','$date')";
						$result = mysqli_query($conn, $sql);

						$sql = "SELECT * FROM Sales;";
						$result = mysqli_query($conn, $sql);
						$resultCheck = mysqli_num_rows($result);
						if($resultCheck > 0)
						{
							echo "<table border = \"1\">";
							echo "<tr><th>SalesId</th><th>ProductId</th><th>Price</th><th>Quantity</th><th>Date</th></tr>";
							while($row = mysqli_fetch_assoc($result))
							{
							echo "<tr><td>";
							echo $row['SalesId'];
							echo "</td><td>";
							echo $row['ProductId'];
							echo "</td><td>";
							echo $row['Price'];
							echo "</td><td>";
							echo $row['Quantity'];
							echo "</td><td>";
							echo $row['Date'];
							echo "</td></tr>";
							}
							echo "</table>";
							}
									// Close everything
									$conn->close();

							} catch(Exception $e) {
									echo "Oops! Something went wrong: ".$e->getMessage();
							}
							?>



<p> Reached </p>


		</section>

	</body>
</html>
