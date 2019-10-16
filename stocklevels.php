<?php
    include_once("settings.php");
    include_once("common.php");

    // connects to the database
    $conn = @mysqli_connect($host, $user, $pwd, $dbnm) or die ('Failed to connect to the database');

    // query to get the stock levels of all of the products
    $query = "SELECT ProductName,Stock FROM Products";

    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

    $dataPoints = array();

    // Stores the result data in a dataPoints array to be used by the chart
    while ($row = $result->fetch_assoc()) {
        $dataPoints[] = array("label" => $row["ProductName"], "y" => intval($row["Stock"]));
    }
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="description" content="People Health Pharmacy: Sales Reporting and Prediction System" />
        <title>PHP-SRePS</title>
        <link href= "style/style.css" rel="stylesheet" />
        <script src="scripts/common.js"></script>
        <script>
            window.onload = function () {

                // Constructs the chart to be displayed using the results retrieved from the database in the above php script
                var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    theme: "light2", // "light1", "light2", "dark1", "dark2"
                    title: {
                        text: "Current Stock Levels"
                    },
                    axisY: {
                        title: "Quantity Remaining",
                        includeZero: false
                    },
                    data: [{
                        type: "column",
                        dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                // Renders the chart in the chartContainter div
                chart.render();

            }
        </script>
    </head>
    <body>
        <header>
            <h1>Stock Information</h1>
        </header>

        <?php echo_nav() ?>

        <section class="centered">
            <div id="chartContainer" style="height: 370px; width: 100%;"></div>
            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
            <?php
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

                // If there are products expected to be depleted within 30 days, display them
                if (count($predicted_empty_month)) {
                    echo "<h2>Products predicted to run out within the next 30 days:</h2>";

                    echo "<ul>";

                    foreach ($predicted_empty_month as $name) {
                        echo "<li>$name</li>";
                    }

                    echo "</ul>";
                }

                // If there are products expected to be depleted within 7 days, display them
                if (count($predicted_empty_week)) {
                    echo "<h2>Products predicted to run out within the next 7 days:</h2>";

                    echo "<ul>";

                    foreach ($predicted_empty_week as $name) {
                        echo "<li>$name</li>";
                    }

                    echo "</ul>";
                }

                // closes the mysql connection
                mysqli_close($conn);
            ?>
        </section>

    </body>
</html>