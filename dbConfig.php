<?php
$apiKey = array("sha1APIkey1","sha1APIkey2");
$servername = "localhost";
$username = "root";
$password = "Deep@0526";
$dbname = "nss";

$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check if there is any error in creating db connection.
if ($mysqli->connect_error) {
  die('Connect Error: Could not connect to database');
}

?>


<?php
//$apiKey = array("sha1APIkey1","sha1APIkey2");
//$servername = "localhost";
//$username = ""; //sql server username here
//$password = ""; //server password here
//-$dbname = ""; // database name here
?>
