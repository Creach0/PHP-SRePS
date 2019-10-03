<?php
  include_once("settings.php");

  $conn = @mysqli_connect($host, $user, $pwd, $dbnm) or die ('Failed to connect to the database');

  $date_in_seconds = strtotime($_POST['end_date']);
  $end_date = date('Y-m-d', $date_in_seconds);
  $length = $_POST['length'];

  switch($length)
  {
    case "week":
      // gets the date a week before
      $start_date = date('Y-m-d', $date_in_seconds - 604800);
      break;
    case "month":
      // gets the date a month before
      $start_date = date('Y-m-d', $date_in_seconds - 2629746);
      break;
  }
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>SRePS</title>
	</head>

	<body>
		<h1>Generated Report</h1>
        <h2>Displaying report from <?php echo $start_date; ?> to <?php echo $end_date; ?></h2>

        <h3>Sales by category</h3>
        <table>
            <tr> <th>Category</th> <th>Total Sales</th> </tr>
            <?php
                $query  = "SELECT CategoryName, (Price * Quantity) AS Total, Date FROM Category NATURAL JOIN Products NATURAL JOIN Sales 
                    WHERE Date > '$start_date' AND Date < '$end_date' GROUP BY CategoryName";

                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

                if (@mysqli_num_rows($result) > 0)
                    while ($row = $result->fetch_assoc())
                    {
                        echo "<tr>"
                            ."<td>".$row["CategoryName"]."</td>"
                            ."<td>".$row["Total"]."</td>"
                            ."</tr>";
                    }
            ?>
        </table>

        <h3>Sales by date</h3>
        <table>
            <tr> <th>Date</th> <th>Total Sales</th> </tr>
            <?php
                $query = "SELECT Date, (Price * Quantity) AS Total FROM Category NATURAL JOIN Products NATURAL JOIN Sales 
                        WHERE Date > '$start_date' AND Date < '$end_date' GROUP BY Date";

                $result = mysqli_query($conn, $query);

                if (@mysqli_num_rows($result) > 0)
                    while ($row = $result->fetch_assoc())
                    {
                        echo "<tr>"
                            ."<td>".$row["Date"]."</td>"
                            ."<td>".$row["Total"]."</td>"
                            ."</tr>";
                    }
            ?>
        </table>

        <h3>Remaining product stocks</h3>
        <table>
            <tr> <th>Product Name</th> <th>Amount Left</th> <th>Amount Sold</th> </tr>
            <?php
                $query = "SELECT Date, Quantity AS TotalSold, Stock, ProductName FROM Products NATURAL JOIN Sales 
                            WHERE Date > '$start_date' AND Date < '$end_date' GROUP BY ProductName";

                $result = mysqli_query($conn, $query);

                if (@mysqli_num_rows($result) > 0)
                    while ($row = $result->fetch_assoc())
                    {
                        echo "<tr>"
                            ."<td>".$row["ProductName"]."</td>"
                            ."<td>".$row["Stock"]."</td>"
                            ."<td>".$row["TotalSold"]."</td>"
                            ."</tr>";
                    }
            ?>
        </table>

	</body>
</html>
