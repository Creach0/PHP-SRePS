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

                // Get sale id
                if (!isset($_POST["saleid"]) ||
                    ($_POST["saleid"] == null) ||
                    !is_numeric($_POST["saleid"])) {
                        throw new Exception("Invalid Sale ID");
                }
                $saleid = htmlspecialchars($_POST["saleid"]);

                // Define varaibles
                $product = "";
                $quantity = "";
                $price = "";
                $date = "";
                $savedata = isset($_POST["save"]) && ($_POST["save"] != null);
                $stmt = null;

                // Check if saving new data
                if ($savedata) {

                    // Check values exist
                    if (!isset($_POST["product"])  || ($_POST["product"] == null)  || !is_string($_POST["product"]) ||
                        !isset($_POST["quantity"]) || ($_POST["quantity"] == null) || !is_numeric($_POST["quantity"]) ||
                        !isset($_POST["price"])    || ($_POST["price"] == null)    || !is_numeric($_POST["price"]) ||
                        !isset($_POST["date"])     || ($_POST["date"] == null)     || !is_string($_POST["date"])) {

                        // Display error message
                        // Abort saving data
                        echo "<p>Invalid data provided</p>";
                        $savedata = false;

                    } else {

                        // Get new data
                        $product  = htmlspecialchars($_POST["product"]);
                        $quantity = htmlspecialchars($_POST["quantity"]);
                        $price    = htmlspecialchars($_POST["price"]);
                        $date     = htmlspecialchars($_POST["date"]);
                    }
                }

                // Connect to database
                require_once ("settings.php");
                $conn = new mysqli($host,$user,$pwd,$dbnm);
                if ($conn->connect_error) throw new Exception("Failed to connect to database: ".$conn->connect_error);

                // Check if saving new data
                if ($savedata) {

                    // Prepare and bind SQL statement
                    $stmt = $conn->prepare("
                        UPDATE Sales
                        SET
                            ProductId = (
                                SELECT ProductId
                                FROM Products
                                WHERE ProductName = ?
                            ),
                            Price = ?,
                            Quantity = ?,
                            Date = ?
                        WHERE SalesId = ?");
                    $stmt->bind_param("sssss", $product, $quantity, $price, $date, $saleid);

                    // Execute statement and check for errors
                    $stmt->execute();
                    $affected_rows = $stmt->affected_rows;
                    if ($affected_rows > 0) {
                        echo "<p>Sale record updated.</p>";
                    } else {
                        echo "<p>Failed to update:<br/>".$stmt->error."</p>";
                    }
                        
                    $quantitydiff = $quantity - mysqli_query($conn, "SELECT Quantity FROM Sales WHERE SaleId = \"$saleid\"");

                    //Subtract from/add existing stock
                    $sql = "UPDATE Products SET Stock = Stock + \"$quantitydiff\" WHERE ProductName = \"$product\"";
                    $result = mysqli_query($conn, $sql);

                    // Close statement
                    $stmt->close();
                }

                // Prepare and bind SQL statement
                $stmt = $conn->prepare("
                    SELECT Products.ProductName, Sales.Price, Sales.Quantity, Sales.Date
                    FROM Sales
                    INNER JOIN Products ON Sales.ProductId=Products.ProductId
                    WHERE Sales.SalesId=?
                    LIMIT 1");
                $stmt->bind_param("s",$saleid);

                // Execute statement and bind results
                $stmt->execute();
                $stmt->bind_result($product,$quantity,$price,$date);

                // Bind and fetch the results
                if ($stmt->fetch()) {
                    echo "
                    <section class=\"centered\">
                        <form id=\"editrecord\" method=\"post\" action=\"editrecord.php\">

                            <label for=\"product\">Product:</label>
                            <input type=\"text\" name=\"product\" id=\"product\" value=\"$product\" /><br />

                            <label for=\"quantity\">Quantity:</label>
                            <input type=\"number\" name=\"quantity\" id=\"quantity\" value=\"$quantity\" /><br />

                            <label for=\"price\">Price:</label>
                            <input type=\"number\" name=\"price\" id=\"price\" value=\"$price\" /><br />

                            <label for=\"date\">Date:</label>
                            <input type=\"date\" name=\"date\" id=\"date\" value=\"$date\" /><br />

                            <input type=\"hidden\" id=\"saleid\" name=\"saleid\" value=\"$saleid\" /><br />

                            <p>
                                <input type=\"submit\" name=\"save\" value=\"Save\" />
                            </p>

                        </form>
                    </section>";
                } else {
                    echo "<p>Invalid sale ID ($saleid).</p>";
                }

                // Close everything
                $stmt->close();
                $conn->close();

            } catch(Exception $e) {
                echo "Oops! Something went wrong: ".$e->getMessage();
            }
		?>
	</body>
</html>
