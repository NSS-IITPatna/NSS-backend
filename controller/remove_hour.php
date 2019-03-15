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

	$removeHours = new Hours($mysqli, $loggedIn);
	$user = new User($mysqli);

	$user->getUser($_POST['roll']);
	//$user->getUser("1701cs80");
	$id = $_POST['hour_id'];
	//$id = 76;
	$remove = $removeHours->removeHours($id);
		
	if ($remove['status']==200) {
		$H_details = $removeHours->getHourDetails($user);

		if ($H_details['status']==200) {
			$total = $removeHours->getTotalHour($H_details['result']);

			$res = array();
			$res['hours_completed'] = $total;
			$res['hours_left'] = $total>=Tot_hours?0:(Tot_hours-$total);
			$res['details'] = $H_details['result'];
			
			$ret['status'] = 200;
			$ret['result'] = $res;
		} else {
			$ret = $H_details;
		}
	} else {
		$ret = $remove;
	}
}

echo json_encode($ret, JSON_PRETTY_PRINT);

?>