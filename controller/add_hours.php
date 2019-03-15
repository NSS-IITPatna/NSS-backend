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

	$addHours = new Hours($mysqli, $loggedIn);
	$user = new User($mysqli);

	$user->getUser($_POST['roll']);
	//$user->getUser("1701cs80");
	$hours = $_POST['hours'];
	$reason = $_POST['reason'];
	$date = isset($_POST['date'])?$_POST['date']:Date('Y-m-d');

	//$addition = $addHours->addNewHours($user, $hours, $reason, $date);
	$addition = $addHours->addNewHours($user, 3, "nota", $date);
		
	if ($addition['status']==200) {
		$H_details = $addHours->getHourDetails($user);

		if ($H_details['status']==200) {
			$total = $addHours->getTotalHour($H_details['result']);

			$res = array();
			$res['roll_no'] = $user->getRollNo();
			$res['name'] = $user->getName();
			$res['cell'] = $user->getCell();
			$res['hours_completed'] = $total;
			$res['hours_left'] = $total>=Tot_hours?0:(Tot_hours-$total);
			$res['details'] = $H_details['result'];
			
			$ret['status'] = 200;
			$ret['result'] = $res;
		} else {
			$ret = $H_details;
		}
	} else {
		$ret = $addition;
	}
}

echo json_encode($ret, JSON_PRETTY_PRINT);

?>