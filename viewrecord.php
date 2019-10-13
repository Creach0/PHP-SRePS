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

                // Get sale ID
                $saleid = (
                    isset($_POST["saleid"]) &&
                    ($_POST["saleid"] != null) &&
                    is_numeric($_POST["saleid"])
                ) ? htmlspecialchars($_POST["saleid"]) : "";

                // Get product name
                $product = (
                    isset($_POST["product"]) &&
                    ($_POST["product"] != null) &&
                    is_string($_POST["product"])
                ) ? htmlspecialchars($_POST["product"]) : "";

                // Get quantity
                $quantity = (
                    isset($_POST["quantity"]) &&
                    ($_POST["quantity"] != null) &&
                    is_numeric($_POST["quantity"])
                ) ? htmlspecialchars($_POST["quantity"]) : "";

                // Get price
                $price = (
                    isset($_POST["price"]) &&
                    ($_POST["price"] != null) &&
                    is_numeric($_POST["price"])
                ) ? htmlspecialchars($_POST["price"]) : "";

                // Get date
                $date = (
                    isset($_POST["date"]) &&
                    ($_POST["date"] != null) &&
                    is_string($_POST["date"])
                ) ? htmlspecialchars($_POST["date"]) : "";

                // Display search fields
                echo "
                <section class=\"centered\">
                    <form id=\"findrecord\" method=\"post\" action=\"viewrecord.php\">

                        <label for=\"saleid\">Sale ID:
                        <input type=\"text\" name=\"saleid\" id=\"saleid\"";
                        if ($saleid != "") echo " value=\"$saleid\""; echo " /></label>(Sale ID overrides other search parameters if used)

                        <hr />

                        <label for=\"product\">Product:
                        <input type=\"text\" name=\"product\" id=\"product\"";
                        if ($product != "") echo " value=\"$product\""; echo " /></label>

                        <label for=\"quantity\">Quantity:
                        <input type=\"number\" name=\"quantity\" id=\"quantity\"";
                        if ($quantity != "") echo " value=\"$quantity\""; echo " /></label>

                        <label for=\"price\">Price:
                        <input type=\"text\" name=\"price\" id=\"price\"";
                        if ($price != "") echo " value=\"$price\""; echo " /></label>

                        <label for=\"date\">Date:
                        <input type=\"date\" name=\"date\" id=\"date\"";
                        if ($date != "") echo " value=\"$date\""; echo " /></label>

                        <input type=\"hidden\" id=\"searched\" name=\"searched\" value=1 />
                        <p>
                            <input type=\"submit\" value=\"Search\" />
                            <input type=\"reset\" value=\"Clear\" />
                        </p>
                    </form>
                </section>";

                // Check if searching for anything
                if (isset($_POST["searched"])) {

                    // Connect to database
                    require_once ("settings.php");
                    $conn = new mysqli($host,$user,$pwd,$dbnm);
                    if ($conn->connect_error) throw new Exception("Failed to connect to database: ".$conn->connect_error);

                    // Update search parameters prior to search
                    $product = "%$product%";
                    $quantity = "%$quantity%";
                    $price = "%$price%";
                    $date = "%$date%";

                    // Prepare and bind SQL statement
                    // Check if searching for a specific SaleID or not
                    $stmt = null;
                    if ($saleid == "") {
                        $stmt = $conn->prepare("
                            SELECT Sales.SalesId, Products.ProductName, Sales.Quantity, Sales.Price, Sales.Date
                            FROM Sales
                            INNER JOIN Products ON Sales.ProductId=Products.ProductId
                            WHERE
                                (Products.ProductName LIKE ?)
                                AND (Sales.Quantity LIKE ?)
                                AND (Sales.Price LIKE ?)
                                AND (Sales.Date LIKE ?)
                            ORDER BY Sales.SalesId");
                        $stmt->bind_param("ssss",$product,$quantity,$price,$date);
                    } else {
                        $stmt = $conn->prepare("
                            SELECT Sales.SalesId, Products.ProductName, Sales.Quantity, Sales.Price, Sales.Date
                            FROM Sales
                            INNER JOIN Products ON Sales.ProductId=Products.ProductId
                            WHERE Sales.SalesId = ?
                            ORDER BY Sales.SalesId");
                        $stmt->bind_param("s",$saleid);
                    }

                    // Execute statement and bind results
                    $stmt->execute();
                    $stmt->bind_result($saleid,$product,$quantity,$price,$date);

                    // Bind and fetch the results
                    echo "
                    <p>
                    <table>
                        <tr>
                            <th>Sale ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Date</th>
                            <th></th>
                        </tr>";
                    while ($stmt->fetch()) {
                        echo "
                        <tr>
                            <td>$saleid</td>
                            <td>$product</td>
                            <td>$quantity</td>
                            <td>$price</td>
                            <td>$date</td>
                            <td>
                                <form id=\"editrecord\" method=\"post\" action=\"editrecord.php\">
                                <input type=\"hidden\" id=\"saleid\" name=\"saleid\" value=\"$saleid\" /><br />
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
                }
            } catch(Exception $e) {
                echo "Oops! Something went wrong: ".$e->getMessage();
            }
		?>
	</body>
</html>
