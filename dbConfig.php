<?php
$apiKey = array("sha1APIkey1","sha1APIkey2");
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nss";

$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check if there is any error in creating db connection.
if ($mysqli->connect_error) {
  die('Connect Error: Could not connect to database');
}

?>

