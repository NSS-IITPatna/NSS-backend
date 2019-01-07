<?php
 header('Access-Control-Allow-Origin: *');  
 define('TIMEZONE', 'Asia/Kolkata');
 date_default_timezone_set(TIMEZONE);

// //Your DB Connection - sample
// $db = new PDO('mysql:host=localhost;dbname=test', 'dbuser', 'dbpassword');
// $db->exec("SET time_zone='$offset';");

/**
* New request lands in this class.
* After that it is routed accordingly to the respective controller. 
*/
class Routing
{
	function __construct()
	{
		return null;
	}
	public function Redirect($url)
	{
		return null;
	}
}

// echo "check";
$url = $_SERVER['REQUEST_URI'];
preg_match('@(.*)index.php(.*)$@', $_SERVER['PHP_SELF'], $mat );
$base = '@^'. $mat[1] ;

if (preg_match($base . '$@', $url, $match)) {
	require ('controller/index.html');
} elseif (preg_match('/request/', $url, $match)) {
	require ('controller/request_blood.php');
}/* elseif (preg_match($base . 'request/?$@', $url, $match)) {
	require ('controller/request_blood.php');
}*/else {
	http_response_code(404);
	require ('controller/404.php');
	// die('invalid url ' . $url);
	die();
}
?>