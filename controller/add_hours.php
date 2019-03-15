<?php
session_start();
header('Content-Type: application/json');	

require_once('../model/hours.php');
require_once('../dbConfig.php');

if(!isset($_SESSION['user'])){
	$ret['status'] = 403;
	$ret['message'] = "Access Forbidden! Please log in first.";
} else {
	$loggedIn = unserialize($_SESSION['user']);

	$addHours = new Hours($mysqli, $loggedIn);

	$user = new User($mysqli);

	//$user->getUser($_POST['roll']);
	$user->getUser("1701cs80");
	$hours = $_POST['hours'];
	$reason = $_POST['reason'];
	$date = isset($_POST['date'])?$_POST['date']:Date('Y-m-d');

	$ret = $addHours->addNewHours($user, 8, "alpha", $date);
}

echo json_encode($ret, JSON_PRETTY_PRINT);

?>