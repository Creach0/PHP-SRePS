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
		<?php
            try {

                // Show nav menu
                echo_nav();

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
                $conn = new mysqli($host,$user,$pwd,$dbnm);
                if ($conn->connect_error) throw new Exception("Failed to connect to database: ".$conn->connect_error);

                // Prepare and bind SQL statement
                $stmt = $conn->prepare("
                    SELECT Sales.SalesId, Products.ProductName, Sales.Price, Sales.Quantity, Sales.Date
                    FROM Sales
                    INNER JOIN Products ON Sales.ProductId=Products.ProductId
                    WHERE
                        Products.ProductName LIKE ?
                        AND Sales.Price LIKE ?
                        AND Sales.Quantity LIKE ?
                        AND Sales.Date LIKE ?
                    ORDER BY Sales.Date, Sales.SalesId");
                $stmt->bind_param("ssss",$product,$quantity,$price,$date);

                echo "
                    <p>Search parameters:<br/>
                    Product: $product<br/>
                    Quantity: $quantity<br/>
                    Price: $price<br/>
                    Date: $date<br/>
                    </p>";
                $stmt->bind_param("sids",$product,$quantity,$price,$date);


                // Execute statement and bind results
                $stmt->execute();
                $stmt->bind_result($saleid,$product,$quantity,$price,$date);

                // Bind and fetch the results
                echo "
                <p>Results:<br/>
                <table>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Date</th>
                    </tr>";
                while ($stmt->fetch()) {
                    echo "
                    <tr>
                        <td>$product</td>
                        <td>$quantity</td>
                        <td>$price</td>
                        <td>$date</td>
                        <td>
                            <form id=\"editrecord\" method=\"post\" action=\"editrecord.php\">
                            <input type=\"hidden\" id=\"saleid\" value=\"$saleid\" /><br />
                            <input type=\"submit\" value=\"Edit\" />
                            </form>
                        </td>
                    </tr>";
                };
                echo "
                </table>
                </p>";

                // Close everything
                $stmt->close();
                $conn->close();

            } catch(Exception $e) {
                echo "Oops! Something went wrong: ".$e->getMessage();
            }
		?>
	</body>
</html>
