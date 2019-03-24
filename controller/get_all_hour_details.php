<?php
session_start();
header('Content-Type: application/json');	
//define('Tot_hours', 80);

require_once('model/hours.php');
require_once('dbConfig.php');

if(!isset($_SESSION['user'])){
	$ret['status'] = 403;
	$ret['message'] = "Access Forbidden! Please log in first.";
} else {
	$loggedIn = unserialize($_SESSION['user']);

	$getHours = new Hours($mysqli, $loggedIn);
	
	$H_details = $getHours->getAllUsersHourDetails();

	if ($H_details['status']==200) {
		$ret['status'] = 200;
		foreach ($H_details['result'] as $key => $value) {
			$total = $getHours->getTotalHour($value);

			$res = array();
			$user = new User($mysqli);
			$user->getUser($key); 
			$res['roll_no'] = $user->getRollNo();
			$res['name'] = $user->getName();
			$res['cell'] = $user->getCell();
			$res['hours_completed'] = $total;
			$res['hours_left'] = $total>=Tot_hours?0:(Tot_hours-$total);
			
			if($value[0]['uid']==null){
				$res['details'] = array();
			} else {
				$res['details'] = $value;		
			}
			$ret['result'][$key] = $res;
		}
	} else {
		$ret = $H_details;
	}
}

echo json_encode($ret, JSON_PRETTY_PRINT);

?>