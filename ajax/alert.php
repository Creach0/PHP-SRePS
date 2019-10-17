<?php
    include_once("../settings.php");

    $conn = @mysqli_connect($host, $user, $pwd, $dbnm) or die ('Failed to connect to the database');

    // query to get all of the products expected to be depleted after 7 days
    $query = "SELECT ProductName, Stock - SUM(Quantity) AS PredictedQuantity FROM 
                                    Products NATURAL JOIN Sales
                                    WHERE Date BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()
                                    GROUP BY ProductName
                                    HAVING PredictedQuantity <= 0
                                    ORDER BY ProductName";

    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

    $predicted_empty_week = array();
    // stores the result in an array
    while ($row = $result->fetch_assoc()) {
        $predicted_empty_week[] = $row["ProductName"];
    }
    // query to get all of the products expected to be depleted in 30 days
    $query = "SELECT ProductName, Stock - SUM(Quantity) AS PredictedQuantity FROM 
                                Products NATURAL JOIN Sales
                                WHERE Date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()
                                GROUP BY ProductName
                                HAVING PredictedQuantity <= 0
                                ORDER BY ProductName";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $predicted_empty_month = array();
    // stores data that doesn't already exist in the previous array into a new array
    while ($row = $result->fetch_assoc()) {
        if(!in_array($row["ProductName"], $predicted_empty_week))
            $predicted_empty_month[] = $row["ProductName"];
    }

    $_7_day_items = count($predicted_empty_week);
    $_30_day_items = count($predicted_empty_month);

    if (!count($predicted_empty_month) && !count($predicted_empty_week)) {
        $output = "There ";
        if ($_7_day_items != 1)
            $output .= "are " . $_7_day_items . " products";
        else
            $output .= "is " . $_7_day_items . " product";
        $output .= " expected to be depleted within the next month and $_30_day_items product";
        if ($_30_day_items != 1)
            $output .= "s";
        $output .= "expected to be depleted within the next 7 days";

        echo $output;
    }
    else echo "";
?>