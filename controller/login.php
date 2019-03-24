<?php
session_start();
header('Content-Type: application/json');

require_once('model/user.php');
require_once('dbConfig.php');

if(isset($_POST['userIdentity']) && isset($_POST['password'])){
	$user = new User($mysqli);

	$mail_or_username = $_POST['userIdentity'];
	$password = $_POST['password'];

	$ret = $user->loginUser($mail_or_username, $password);

	//$ret = $user->loginUser("1701cs17", "1234");
	
	$_SESSION['user'] = serialize($user);
	$_SESSION['uroll'] = $user->getRollNo();
} else {
	$ret['status'] = 403;
	$ret['message'] = "Request Denied";
}
echo json_encode($ret, JSON_PRETTY_PRINT);

?>