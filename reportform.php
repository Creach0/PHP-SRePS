<?php include_once("common.php"); ?>

<!DOCTYPE html>
<html lang="en">
    <?php echo_head(); ?>
    <body>
    <header>
        <h1>View Reports</h1>
    </header>
    <?php echo_nav(); ?>

	<body>
		<h1>Select Report Specifications</h1>
    <form method="post" action="reportdisplay.php">
	    <table>
	      <tr>
	        <td>End Date:</td>
	        <td colspan="2"><input type="date" name="end_date"/></td>
	      </tr>
	      <tr>
	        <td>Length:</td>
	        <td><input type="radio" name="length" id="week" value="week"/><label for="week">Week</label></td>
	        <td><input type="radio" name="length" id="month" value="month"/><label for="month">Month</label></td>
	      </tr>
	      <tr>
	        <td colspan="3">
	          <input type="submit" class="button" value="Generate Report"/>
	          <input type="reset" class="button"/>
	        </td>
	      </tr>
	    </table>
    </form>
        <div id="stockAlert"></div>
	</body>
</html>
