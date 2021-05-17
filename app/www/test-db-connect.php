<?php

require_once("./incl/DBParam.php");

$mysqli_connection = new MySQLi($dbHost, $dbUser, $dbPassword, $dbName, $port);
if ($mysqli_connection->connect_error) {
    echo "Not connected, error: " . $mysqli_connection->connect_error;
}
else {
    echo "Connected.";
}
