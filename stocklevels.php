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

    mysqli_close($conn);
?>
<!DOCTYPE HTML>
<html>
    <head>
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
        </section>

    </body>
</html>