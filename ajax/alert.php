<?php
    include_once("../settings.php");

    $conn = @mysqli_connect($host, $user, $pwd, $dbnm) or die ('Failed to connect to the database');

    echo "Hello";
?>