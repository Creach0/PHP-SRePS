<?php
	session_start();
	include_once("common.php");
?>
<!DOCTYPE html>
<html lang="en">
	<?php echo_head(); ?>
    <head>
		<script src="scripts/EditRecordValidateInput.js"></script>
    </head>
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
                        throw new Exception("Invalid Sale ID. Try <a href=\"findrecord.php\">searching for a record</a> first.");
                }
                $saleid = htmlspecialchars($_POST["saleid"]);

                // Define varaibles
                $product = "";
                $quantity = "";
                $price = "";
                $date = "";
                $savedata = isset($_POST["save"]) && ($_POST["save"] != null);
                $deleterecord = isset($_POST["deleterecord"]) && ($_POST["deleterecord"] == "yes");
                $stmt = null;

                // Check if saving new data
                if ($savedata && !$deleterecord) {

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

                // Check if deleting record
                if ($deleterecord) {

                    // Prepare and bind SQL statement
                    $stmt = $conn->prepare("
                        DELETE FROM Sales
                        WHERE SalesId = ?");
                    $stmt->bind_param("s", $saleid);

                    // Execute statement and check for errors
                    $res = $stmt->execute();
                    if ($res == false) {
                        echo "<p>Failed to delete sales record.</p>";
                    } else {
                        $affected_rows = $stmt->affected_rows;
                        if ($affected_rows > 0) {
                            echo "<p>Sale record $saleid was deleted.</p>";
                        } else {
                            echo "<p>No changes made.</p>";
                        }
                    }

                    // Close statement
                    $stmt->close();
                } else {

                    // Check if saving new data
                    if ($savedata) {
                        if ($_POST['inputValid'] == "true")
                        {
                            // Prepare and bind SQL statement
                            $stmt = $conn->prepare("
                                UPDATE Sales
                                SET
                                    ProductId = (
                                        SELECT ProductId
                                        FROM Products
                                        WHERE ProductName = ?
                                    ),
                                    Quantity = ?,
                                    Price = ?,
                                    Date = ?
                                WHERE SalesId = ?");
                            $stmt->bind_param("sssss", $product, $quantity, $price, $date, $saleid);

                            // Execute statement and check for errors
                            $res = $stmt->execute();
                            if ($res == false) {
                                echo "<p>Failed to update sales record.</p>";
                            } else {
                                $affected_rows = $stmt->affected_rows;
                                if ($affected_rows > 0) {
                                    echo "<p>Sale record $saleid was updated.</p>";
                                } else {
                                    echo "<p>No changes made.</p>";
                                }
                            }

                            // Close statement
                            $stmt->close();
                        }
                    }

                    // Prepare and bind SQL statement
                    $stmt = $conn->prepare("
                        SELECT Products.ProductName, Sales.Quantity, Sales.Price, Sales.Date
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

                                <label for=\"saleid\">Sale ID:
                                <input type=\"text\" name=\"saleid\" id=\"saleid\" value=\"$saleid\" readonly /></label><br />

                                <label for=\"product\">Product:
                                <input type=\"text\" name=\"product\" id=\"product\" value=\"$product\" /></label><br />

                                <label for=\"quantity\">Quantity:
                                <input type=\"text\" name=\"quantity\" id=\"quantity\" value=\"$quantity\" /></label><br />

                                <label for=\"price\">Price:
                                <input type=\"text\" name=\"price\" id=\"price\" value=\"$price\" /></label><br />

                                <label for=\"date\">Date:
                                <input type=\"date\" name=\"date\" id=\"date\" value=\"$date\" /></label><br />

                                <label for=\"deleterecord\">Delete sales record:
                                <input type=\"checkbox\" name=\"deleterecord\" id=\"deleterecord\" value=\"yes\" /></label><br />
                                
                                <input type=\"hidden\" id=\"inputValid\" name=\"inputValid\" />

                                <p>
                                    <input type=\"submit\" name=\"save\" value=\"Save\" onclick=\"validateInput()\" />
                                </p>

                            </form>
                        </section>";
                    } else {
                        echo "<p>Invalid sale ID ($saleid).</p>";
                    }

                    // Close statement
                    $stmt->close();
                }

                // Close connection to database
                $conn->close();

            } catch(Exception $e) {
                echo "Oops! Something went wrong: ".$e->getMessage();
            }
		?>
	</body>
</html>
