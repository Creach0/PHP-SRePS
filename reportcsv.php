<?php

    // Headers to tell the browser to treat this document as a CSV file
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=report.csv");

    // Get inputs
    if (!isset($_POST["start_date"]) || !isset($_POST["end_date"])) die();
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];

    // Connect to database
    require_once("settings.php");
    $conn = new mysqli($host,$user,$pwd,$dbnm);
    if ($conn->connect_error) throw new Exception("Failed to connect to database: ".$conn->connect_error);


    /////////////////////////////////////////
    // Prepare and bind SQL statement (first)
    $stmt = $conn->prepare("
        SELECT CategoryName, SUM(Price * Quantity) AS Total
        FROM Category
        NATURAL JOIN Products
        NATURAL JOIN Sales
        WHERE Date >= ? AND Date <= ?
        GROUP BY CategoryName");
    $stmt->bind_param("ss",$start_date,$end_date);

    // Execute statement and bind results
    $stmt->execute();
    $stmt->bind_result($category,$total);

    // Output results to CSV file
    echo "\"Sales by category\"\n";
    echo "\"Category\",\"Total Sales\"\n";
    while ($stmt->fetch()) {
        echo "\"$category\",\$$total\n";
    };
    echo "\n";

    // Delete prepared statement
    $stmt->close();


    /////////////////////////////////////////
    // Prepare and bind SQL statement (second)
    $stmt = $conn->prepare("
        SELECT Date, SUM(Price * Quantity) AS Total
        FROM Category
        NATURAL JOIN Products
        NATURAL JOIN Sales
        WHERE Date >= ? AND Date <= ?
        GROUP BY Date");
    $stmt->bind_param("ss",$start_date,$end_date);

    // Execute statement and bind results
    $stmt->execute();
    $stmt->bind_result($date,$total);

    // Output results to CSV file
    echo "\"Sales by date\"\n";
    echo "\"Date\",\"Total Sales\"\n";
    while ($stmt->fetch()) {
        echo "\"$date\",\$$total\n";
    };
    echo "\n";

    // Delete prepared statement
    $stmt->close();


    /////////////////////////////////////////
    // Prepare and bind SQL statement (third)
    $stmt = $conn->prepare("
        SELECT SUM(Quantity) AS TotalSold, Stock, ProductName
        FROM Products
        NATURAL JOIN Sales
        WHERE Date >= ? AND Date <= ?
        GROUP BY ProductName");
    $stmt->bind_param("ss",$start_date,$end_date);

    // Execute statement and bind results
    $stmt->execute();
    $stmt->bind_result($total,$stock,$product);

    // Output results to CSV file
    echo "\"Remaining product stocks\"\n";
    echo "\"Product Name\",\"Amount Left\",\"Remaining product stocks\"\n";
    while ($stmt->fetch()) {
        echo "\"$product\",$stock,\$$total\n";
    };
    echo "\n";

    // Delete prepared statement
    $stmt->close();


    // Close database connection
    $conn->close();
?>
