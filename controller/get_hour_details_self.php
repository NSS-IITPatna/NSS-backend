<?php
session_start();
header('Content-Type: application/json');	
//define('Tot_hours', 80);

require_once('../model/hours.php');
require_once('../dbConfig.php');

if(!isset($_SESSION['user'])){
	$ret['status'] = 403;
	$ret['message'] = "Access Forbidden! Please log in first.";
} else {
	$loggedIn = unserialize($_SESSION['user']);

	$getHours = new Hours($mysqli, $loggedIn);
	
	$H_details = $getHours->getHourDetails($loggedIn);

	if ($H_details['status']==200) {
		$total = $getHours->getTotalHour($H_details['result']);

		$res = array();
		$res['roll_no'] = $loggedIn->getRollNo();
		$res['name'] = $loggedIn->getName();
		$res['cell'] = $loggedIn->getCell();
		$res['hours_completed'] = $total;
		$res['hours_left'] = $total>=Tot_hours?0:(Tot_hours-$total);
		$res['details'] = $H_details['result'];
		
		$ret['status'] = 200;
		$ret['result'] = $res;
	} else {
		$ret = $H_details;
	}
}

echo json_encode($ret, JSON_PRETTY_PRINT);

?>