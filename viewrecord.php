<?php
	session_start();
	include_once("common.php");
	include_once("settings.php");
?>
<!DOCTYPE html>
<html lang="en">
	<?php echo_head(); ?>
	<body>
		<header>
			<h1>Sales Records</h1>
		</header>
		<?php
            try {

                // Get product name
                $product = (
                    isset($_POST["product"]) &&
                    ($_POST["product"] != null) &&
                    is_string($_POST["product"])
                ) ? ("%".htmlspecialchars($_POST["product"])."%") : "%";

                // Get quantity
                $quantity = (
                    isset($_POST["quantity"]) &&
                    ($_POST["quantity"] != null) &&
                    is_integer($_POST["quantity"])
                ) ? ("%".htmlspecialchars($_POST["quantity"])."%") : "%";

                // Get price
                $price = (
                    isset($_POST["price"]) &&
                    ($_POST["price"] != null) &&
                    is_numeric($_POST["price"])
                ) ? ("%".htmlspecialchars($_POST["price"])."%") : "%";

                // Get date
                $date = (
                    isset($_POST["date"]) &&
                    ($_POST["date"] != null) &&
                    is_string($_POST["date"])
                ) ? ("%".htmlspecialchars($_POST["date"])."%") : "%";

                // Connect to database
                require_once ("settings.php");
                $conn = @mysqli_connect($host,$user,$pwd,$dbnm);
                if (!$conn) throw new Exception("Failed to connect to database");

                // Show nav menu
                echo_nav();




                // close the database connection
                mysqli_close($conn);

            } catch(Exception $e) {
                echo "Oops! Something went wrong: " . $e->getMessage();
            }
		?>
	</body>
</html>
