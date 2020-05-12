
<?php

$dbServerName = "localhost";
$dbUserName = "root";
$dbPassword = "root";
$dbName = "meddb";

$conn = mysqli_connect($dbServerName, $dbUserName, $dbPassword, $dbName) or die("Bad connection: ".mysqli_connect_error());

?>

