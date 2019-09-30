<?php
  include_once("settings.php");

  //$conn = mysqli_connect($host, $user, $pwd, $database) or die ('Failed to connect to the database');

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

	</body>
</html>
