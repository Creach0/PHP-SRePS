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

                // Show query form
                echo "
                <p><form id=\"rawquery\" method=\"post\" action=\"showall.php\">
                    <label for=\"query\">Database Query:
                    <input type=\"text\" name=\"query\" id=\"query\" /></label>
                    <input type=\"submit\" value=\"Run\" />
                </form> (Run a blank query to clear the page to default)</p>";

                // Connect to database
                require_once ("settings.php");
                $conn = new mysqli($host,$user,$pwd,$dbnm);
                if ($conn->connect_error) throw new Exception("Failed to connect to database: ".$conn->connect_error);

                /////////////////////////////////////////
                // Check for previously submitted query request
                if (isset($_POST["query"]) && ($_POST["query"] != "")) {
                    $query = htmlspecialchars($_POST["query"]);

                    // Display previous query
                    echo "<p>Previous Query: $query</p><br />";

                    // Run query
                    $result = $conn->query($query);

                    // Display result
                    echo "<p>".$result->num_rows." results.</p>";
                    if ($result->num_rows > 0) {

                        // Get field information
                        $fieldinfo = $result->fetch_fields();
                        echo "<p><table><tr>";
                        foreach($fieldinfo as $val) {
                            echo "<th>".$val->name."</th>";
                        }
                        echo "</tr>";

                        // Get rows
                        while ($row = $result->fetch_row()) {
                            echo "<tr>";
                            foreach($row as $val) {
                                echo "<td>$val</td>";
                            }
                            echo "</tr>";
                        }
                        echo "</table></p>";
                    }
                } else {

                    /////////////////////////////////////////
                    // Prepare and bind SQL statement (first)
                    $stmt = $conn->prepare("
                        SELECT CategoryId,CategoryName
                        FROM Category");

                    // Execute statement and bind results
                    $stmt->execute();
                    $stmt->bind_result($categoryid,$categoryname);

                    // Bind and fetch the results
                    echo "
                    <br/><p>Category:<br/>
                    <table>
                        <tr>
                            <th>CategoryId</th>
                            <th>CategoryName</th>
                        </tr>";
                    while ($stmt->fetch()) {
                        echo "
                        <tr>
                            <td>$categoryid</td>
                            <td>$categoryname</td>
                        </tr>";
                    };
                    echo "
                    </table>
                    </p>";

                    // Delete prepared statement
                    $stmt->close();


                    /////////////////////////////////////////
                    // Prepare and bind SQL statement (second)
                    $stmt = $conn->prepare("
                        SELECT ProductId,ProductName,CategoryId,Stock
                        FROM Products");

                    // Execute statement and bind results
                    $stmt->execute();
                    $stmt->bind_result($productid,$productname,$categoryid,$stock);

                    // Bind and fetch the results
                    echo "
                    <br/><p>Products:<br/>
                    <table>
                        <tr>
                            <th>ProductId</th>
                            <th>ProductName</th>
                            <th>CategoryId</th>
                            <th>Stock</th>
                        </tr>";
                    while ($stmt->fetch()) {
                        echo "
                        <tr>
                            <td>$productid</td>
                            <td>$productname</td>
                            <td>$categoryid</td>
                            <td>$stock</td>
                        </tr>";
                    };
                    echo "
                    </table>
                    </p>";

                    // Delete prepared statement
                    $stmt->close();


                    /////////////////////////////////////////
                    // Prepare and bind SQL statement (third)
                    $stmt = $conn->prepare("
                        SELECT SalesId,ProductId,Price,Quantity,Date
                        FROM Sales");

                    // Execute statement and bind results
                    $stmt->execute();
                    $stmt->bind_result($saleid,$product,$quantity,$price,$date);

                    // Bind and fetch the results
                    echo "
                    <br/><p>Sales:<br/>
                    <table>
                        <tr>
                            <th>SaleId</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Date</th>
                        </tr>";
                    while ($stmt->fetch()) {
                        echo "
                        <tr>
                            <td>$saleid</td>
                            <td>$product</td>
                            <td>$quantity</td>
                            <td>$price</td>
                            <td>$date</td>
                        </tr>";
                    };
                    echo "
                    </table>
                    </p>";

                    // Delete prepared statement
                    $stmt->close();
                }


                // Close database connection
                $conn->close();

            } catch(Exception $e) {
                echo "Oops! Something went wrong: ".$e->getMessage();
            }
		?>
	</body>
</html>
