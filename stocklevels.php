<?php
    include_once("settings.php");
    include_once("common.php");

    // connects to the database
    $conn = @mysqli_connect($host, $user, $pwd, $dbnm) or die ('Failed to connect to the database');

    $query = "SELECT ProductName,Stock FROM Products";

    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

    $dataPoints = array();

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


                $query = "SELECT ProductName, SUM(Quantity) AS QuantitySold FROM 
                            Products JOIN Sales ON Products.ProductId = Sales.ProductId
                            WHERE Date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()
                            GROUP BY ProductName
                            ORDER BY ProductName";

                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

                echo mysqli_num_rows($result). "  <br/>";

                while ($row = $result->fetch_assoc()) {
                    var_dump($row["ProductName"]);
                    echo "<br/>";
                    var_dump($row["QuantitySold"]);
                }

                mysqli_close($conn);
            ?>
        </section>

    </body>
</html>