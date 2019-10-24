<?php
	session_start();
	include_once("common.php");
?>
<!DOCTYPE html>
<html lang="en">
	<?php echo_head(); ?>
	<body>
		<header>
			<h1>Add Product Stock</h1>
		</header>
		<?php
            try {

                // Show nav menu
                echo_nav();

                // Get product name
                $product = (
                    isset($_POST["product"]) &&
                    ($_POST["product"] != null) &&
                    is_string($_POST["product"])
                ) ? htmlspecialchars($_POST["product"]) : null;

                // Get quantity
                $quantity = (
                    isset($_POST["quantity"]) &&
                    ($_POST["quantity"] != null) &&
                    is_numeric($_POST["quantity"])
                ) ? htmlspecialchars($_POST["quantity"]) : null;

                // Connect to database
                require_once ("settings.php");
                $conn = new mysqli($host,$user,$pwd,$dbnm);
                if ($conn->connect_error) throw new Exception("Failed to connect to database: ".$conn->connect_error);

                // Prepare and bind SQL statement
                // Get a list of all valid products
                $stmt = $conn->prepare("
                    SELECT ProductName
                    FROM Products
                    ORDER BY ProductName");

                // Execute statement and buffer the result set
                $stmt->execute();
                $stmt->store_result();

                // Bind and fetch the results
                // Display form used to add stock for a specific product
                $stmt->bind_result($validProduct);
                echo "
                <section class=\"centered\">
                    <form id=\"addstock\" method=\"post\" action=\"addstock.php\">

                        <label for=\"product\">Product:
                        <select name=\"product\" id=\"product\">";
                        while ($stmt->fetch()) {
                            $vp = htmlspecialchars($validProduct);
                            $s = ($validProduct == $product) ? " selected " : "";
                            echo "<option value=\"$vp\"$s>$vp</option>";
                        };
                        echo "</select></label>

                        <label for=\"quantity\">Quantity to add:
                        <input type=\"number\" name=\"quantity\" id=\"quantity\" value=0 /></label>

                        <p>
                            <input type=\"submit\" value=\"Add\" />
                        </p>
                    </form>
                </section>";

                // Close statement
                $stmt->close();

                // Check if stock was just added
                if (($product != null) &&
                    ($quantity != null)) {

                    // Prepare and bind SQL statement (update stock value)
                    $stmtAdd = $conn->prepare("
                        UPDATE Products
                        SET Stock = Stock + ?
                        WHERE ProductName = ?");
                    $stmtAdd->bind_param("is", $quantity, $product);

                    // Prepare and bind SQL statement (get updated value)
                    $stmtGet = $conn->prepare("
                        SELECT Stock
                        FROM Products
                        WHERE ProductName = ?");
                    $stmtGet->bind_param("s", $product);

                    // Execute statement and check for errors (update stock value)
                    $res = $stmtAdd->execute();
                    if ($res == false) {
                        echo "<p>Failed to update product data.</p>";
                    } else {

                        // Execute statement and check for errors (get updated value)
                        $res = $stmtGet->execute();
                        if ($res == false) {
                            echo "<p>Failed to get product data.</p>";
                        } else {

                            // Bind and fetch the results (get updated value)
                            $stmtGet->bind_result($stock);
                            $res = $stmtGet->fetch();
                            if ($res != true) $stock = "(error)";

                            // Get the number of affected rows
                            // If none then no changes were made
                            if ($stmtAdd->affected_rows == 0) {
                                echo "<p>No changes were made. The current stock of <b>$product</b> is $stock.</p>";
                            } else {
                                if ($quantity > 0) $quantity = "+".$quantity;
                                echo "<p>Current stock of <b>$product</b> has changed by $quantity, and is now $stock.</p>";
                            }
                        }
                    }

                    // Close statement
                    $stmtGet->close();
                    $stmtAdd->close();
                }

                // Close connection to database
                $conn->close();

            } catch(Exception $e) {
                echo "Oops! Something went wrong: ".$e->getMessage();
            }
		?>
	</body>
</html>
