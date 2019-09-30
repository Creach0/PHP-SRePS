<?php
  include_once("settings.php");

  $conn = @mysqli_connect($host, $user, $pwd, $dbnm)
    or die('Failed to connect to server : ' . mysql_error());

  //Set up SQL command to query or add data into the table
  $query = "SELECT testField FROM testTbl;";

  //Execute the query and store the result into the result pointer
  $result = @mysqli_query($conn, $query)
  	or die("<p>Unable to execute the query.</p>"
  	. "<p>Error code " . mysql_errno($conn)
  	. ": " . mysql_error($conn)) . "</p>";

  $row = mysqli_fetch_assoc($result);

  echo $row["testField"];
?>
