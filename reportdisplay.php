<?php
  include_once("settings.php");

  // connects to the database
  $conn = @mysqli_connect($host, $user, $pwd, $dbnm) or die ('Failed to connect to the database');

  // gets the unix timestamp to allow the start date to be calculated
  $date_in_seconds = strtotime($_POST['end_date']);

  // gets the end date in the correct format
  $end_date = date('Y-m-d', $date_in_seconds);

  // gets whether the length is weekly or monthly from the form
  $length = $_POST['length'];

  // calculates the end date based on whether the user chose to generate a weekly or monthly report
  switch($length)
  {
    case "week":
      // gets the date a week before
      $start_date = date('Y-m-d', $date_in_seconds - 432000);
      break;
    case "month":
      // gets the date a month before
      $start_date = date('Y-m-d', $date_in_seconds - 2629746);
      break;
  }
?>

<!DOCTYPE html>
<html lang="en">
    <?php echo_head(); ?>
    <body>
    <header>
        <h1>View Reports</h1>
    </header>
    <?php echo_nav() ?>

	<body>
		<h1>Generated Report</h1>
        <h2>Displaying report from <?php echo $start_date; ?> to <?php echo $end_date; ?></h2>

        <h3>Sales by category</h3>
        <table>
            <tr> <th>Category</th> <th>Total Sales</th> </tr>
            <?php
                // query to get the total sales for each category
                $query  = "SELECT CategoryName, SUM(Price * Quantity) AS Total, Date FROM Category NATURAL JOIN Products NATURAL JOIN Sales 
                    WHERE Date >= '$start_date' AND Date <= '$end_date' GROUP BY CategoryName";

                $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

                if (@mysqli_num_rows($result) > 0)
                    while ($row = $result->fetch_assoc())
                    {
                        echo "<tr>"
                            ."<td>".$row["CategoryName"]."</td>"
                            ."<td>$".$row["Total"]."</td>"
                            ."</tr>";
                    }
            ?>
        </table>

        <h3>Sales by date</h3>
        <table>
            <tr> <th>Date</th> <th>Total Sales</th> </tr>
            <?php
                // query to get the total sales for each date
                $query = "SELECT Date, SUM(Price * Quantity) AS Total FROM Category NATURAL JOIN Products NATURAL JOIN Sales 
                        WHERE Date >= '$start_date' AND Date <= '$end_date' GROUP BY Date";

                $result = mysqli_query($conn, $query);

                if (@mysqli_num_rows($result) > 0)
                    while ($row = $result->fetch_assoc())
                    {
                        echo "<tr>"
                            ."<td>".$row["Date"]."</td>"
                            ."<td>$".$row["Total"]."</td>"
                            ."</tr>";
                    }
            ?>
        </table>

        <h3>Remaining product stocks</h3>
        <table>
            <tr> <th>Product Name</th> <th>Amount Left</th> <th>Amount Sold</th> </tr>
            <?php
                // query to get the stock and amount sold for each product
                $query = "SELECT Date, SUM(Quantity) AS TotalSold, Stock, ProductName FROM Products NATURAL JOIN Sales 
                            WHERE Date >= '$start_date' AND Date <= '$end_date' GROUP BY ProductName";

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

<?php @mysqli_close($conn) ?>
