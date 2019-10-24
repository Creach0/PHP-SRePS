<?php

    // Headers to tell the browser to treat this document as a CSV file
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=salerecords.csv");

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

    // Connect to database
    require_once("settings.php");
    $conn = new mysqli($host,$user,$pwd,$dbnm);
    if ($conn->connect_error) throw new Exception("Failed to connect to database: ".$conn->connect_error);

    // Update search parameters prior to search
    $product = ($product == null) ? "%" : "%$product%";

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
        $stmt->bind_param("sssssss",$product,$quantity,$quantity,$price,$price,$date,$date);
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
    if ($stmt->num_rows != 0) {
        $stmt->bind_result($saleid,$product,$quantity,$price,$date);
        echo "\"Sale ID\",\"Product\",\"Quantity\",\"Price\",\"Date\"\n";
        while ($stmt->fetch()) {
            echo "$saleid,\"$product\",$quantity,\$$price,$date\n";
        };
    }

    // Close everything
    $stmt->close();
    $conn->close();
?>
