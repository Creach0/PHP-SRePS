<?php

	// Echo common head and meta data
	function echo_head() {
		echo '<head>
		<meta charset="utf-8" />
		<meta name="description" content="People Health Pharmacy: Sales Reporting and Prediction System" />
		<title>PHP-SRePS</title>
		<link href= "style/style.css" rel="stylesheet" />
		<script src="scripts/common.js"></script>
		<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
        	<script src="scripts/alertChecker.js"></script>
	</head>';
	}

	// Echo nav menu
	function echo_nav() {

		// Static nav menu section
		echo '<nav><ul>
		<li><a href="index.php">Home</a></li>
		<li><a href="findrecord.php">Find Sales Record</a></li>
		<li><a href="addrecord.php">Add Sales Record</a></li>
		<li><a href="reportform.php">Generate Sales Report</a></li>
		<li><a href="addnewproduct.php">Add a New Product</a></li>
		<li><a href="addstock.php">Add Stock to a Product
		<li><a href="showall.php">Show All Database</a></li>
		</ul></nav>';
	}
?>
