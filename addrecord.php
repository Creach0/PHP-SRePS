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


		<section class="centered">
			<form id="addrecord" method="post" action="addrecord.php">

				<label for="product">Product:
				<input type="text" name="product" id="product" /></label><br />

				<label for="quantity">Quantity:
				<input type="number" name="quantity" id="quantity" /></label><br />

				<label for="price">Price:
				<input type="number" name="price" id="price" /></label><br />

				<label for="date">Date:
				<input type="date" name="date" id="date" /></label><br />

				<p>
					<input type="submit" value="Search" />
					<input type="reset" value="Clear" />
				</p>

			</form>

			<?php
				try {

						// Get product name
						$product = (
								isset($_POST["product"]) &&
								($_POST["product"] != null) &&
								is_string($_POST["product"])
						) ? ("%".htmlspecialchars($_POST["product"])."%") : "%";

						echo "$product = $product";

						// Connect to database
						require_once ("settings.php");
						$conn = new mysqli($host,$user,$pwd,$dbnm);
						if ($conn->connect_error) throw new Exception("Failed to connect to database: ".$conn->connect_error);

						echo "<p>Connected to database.</p>";


						// Close everything
						$conn->close();

				} catch(Exception $e) {
						echo "Oops! Something went wrong: ".$e->getMessage();
				}
				?>


				<?php
				//Retrieve db
					$sql = "SELECT * FROM Sales;";
		$result = mysqli_query($conn, $sql);
		$resultCheck = mysqli_num_rows($result);
		if($resultCheck > 0)
		{
			echo "<table border = \"1\">";
			echo "<tr><th>SalesId</th><th>ProductId</th><th>Price</th><th>Quantity</th><th>Date</th></tr>";
			while($row = mysqli_fetch_assoc($result)) {
			echo "<tr><td>";
			echo $row['SalesId'];
			echo "</td><td>";
			echo $row['o'];
			echo "</td></tr>"; }
			echo "</table>"; }
				 ?>



		</section>

	</body>
</html>
