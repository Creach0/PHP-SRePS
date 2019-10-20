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
                ) ? htmlspecialchars($_POST["saleid"]) : null;

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

                // Get price
                $price = (
                    isset($_POST["price"]) &&
                    ($_POST["price"] != null) &&
                    is_numeric($_POST["price"])
                ) ? htmlspecialchars($_POST["price"]) : null;

                // Get date
                $date = (
                    isset($_POST["date"]) &&
                    ($_POST["date"] != null) &&
                    is_string($_POST["date"])
                ) ? htmlspecialchars($_POST["date"]) : null;

                // Display search fields
                echo "
                <section class=\"centered\">
                    <form id=\"findrecord\" method=\"post\" action=\"findrecord.php\">

                        <label for=\"saleid\">Sale ID:
                        <input type=\"text\" name=\"saleid\" id=\"saleid\"";
                        if ($saleid != null) echo " value=\"$saleid\""; echo " /></label>(Sale ID overrides other search parameters if used)

                        <hr />

                        <label for=\"product\">Product:
                        <input type=\"text\" name=\"product\" id=\"product\"";
                        if ($product != null) echo " value=\"$product\""; echo " /></label>

                        <label for=\"quantity\">Quantity:
                        <input type=\"number\" name=\"quantity\" id=\"quantity\"";
                        if ($quantity != null) echo " value=\"$quantity\""; echo " /></label>

                        <label for=\"price\">Price:
                        <input type=\"text\" name=\"price\" id=\"price\"";
                        if ($price != null) echo " value=\"$price\""; echo " /></label>

                        <label for=\"date\">Date:
                        <input type=\"date\" name=\"date\" id=\"date\"";
                        if ($date != null) echo " value=\"$date\""; echo " /></label>

                        <input type=\"hidden\" id=\"searched\" name=\"searched\" value=1 />
                        <p>
                            <input type=\"submit\" value=\"Search\" />
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
                    $productSearch = ($product == null) ? "%" : "%$product%";

                    // Prepare and bind SQL statement
                    // Check if searching for a specific SaleID or not
                    $stmt = null;
                    if (($saleid == null) || ($saleid == "")) {
                        $stmt = $conn->prepare("
                            SELECT Sales.SalesId, Products.ProductName, Sales.Quantity, Sales.Price, Sales.Date
                            FROM Sales
                            INNER JOIN Products ON Sales.ProductId=Products.ProductId
                            WHERE
                                (Products.ProductName LIKE ?)
                                AND ((? IS NULL) OR (Sales.Quantity = ?))
                                AND ((? IS NULL) OR (Sales.Price = ?))
                                AND ((? IS NULL) OR (Sales.Date = ?))
                            ORDER BY Sales.SalesId");
                        $stmt->bind_param("sssssss",$productSearch,$quantity,$quantity,$price,$price,$date,$date);
                    } else {
                        $stmt = $conn->prepare("
                            SELECT Sales.SalesId, Products.ProductName, Sales.Quantity, Sales.Price, Sales.Date
                            FROM Sales
                            INNER JOIN Products ON Sales.ProductId=Products.ProductId
                            WHERE Sales.SalesId = ?
                            ORDER BY Sales.SalesId");
                        $stmt->bind_param("s",$saleid);
                    }

                    // Execute statement and buffer the result set
                    $stmt->execute();
                    $stmt->store_result();


                    // Bind and fetch the results
                    if ($stmt->num_rows == 0) {
                        echo "<p>No results found.</p>";
                    } else {

                        // Show button to download results as a CSV
                        echo "
                        <form id=\"findrecordcsv\" method=\"post\" action=\"findrecordcsv.php\">
                            <input type=\"hidden\" name=\"saleid\" id=\"saleid\""; if ($saleid != null) echo " value=\"$saleid\""; echo " />
                            <input type=\"hidden\" name=\"product\" id=\"product\""; if ($product != null) echo " value=\"$product\""; echo " />
                            <input type=\"hidden\" name=\"quantity\" id=\"quantity\""; if ($quantity != null) echo " value=\"$quantity\""; echo " />
                            <input type=\"hidden\" name=\"price\" id=\"price\""; if ($price != null) echo " value=\"$price\""; echo " />
                            <input type=\"hidden\" name=\"date\" id=\"date\""; if ($date != null) echo " value=\"$date\""; echo " />
                            <input type=\"submit\" value=\"Download results as CSV file\" />
                        </form>";

                        // Show search results
                        $stmt->bind_result($saleid,$product,$quantity,$price,$date);
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
                    }

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
