<?php

	// Echo common head and meta data
	function echo_head() {
		echo '<head>
		<meta charset="utf-8" />
		<meta name="description" content="People Health Pharmacy: Sales Reporting and Prediction System" />
		<title>PHP-SRePS</title>
		<link href= "style/style.css" rel="stylesheet" />
		<script src="scripts/common.js"></script>
	</head>';
	}

	// Echo nav menu
	function echo_nav() {

		// Static nav menu section
		echo '<nav><ul>
		<li><a href="index.php">Home</a></li>
		<li><a href="vewrecord.php">Find Sales Record</a></li>
		</ul></nav>';
	}

?>