<?php
 header('Access-Control-Allow-Origin: *');  
 define('TIMEZONE', 'Asia/Kolkata');
 date_default_timezone_set(TIMEZONE);
 define('Tot_hours', 80);
// $now = new DateTime();
// $mins = $now->getOffset() / 60;
// $sgn = ($mins < 0 ? -1 : 1);
// $mins = abs($mins);
// $hrs = floor($mins / 60);
// $mins -= $hrs * 60;
// $offset = sprintf('%+d:%02d', $hrs*$sgn, $mins);

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
} elseif (preg_match('/think_thank/', $url, $match)) {
	require ('controller/think_thank.php');
} elseif (preg_match($base . 'think_thank/?$@', $url, $match)) {
	require ('controller/think_thank.php');
} elseif (preg_match($base . 'request/?$@', $url, $match)) {
	require ('controller/request_blood.php');
} else {
	http_response_code(404);
	require ('controller/404.php');
	// die('invalid url ' . $url);
	die();
}
?>