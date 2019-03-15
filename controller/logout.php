<?php

session_start();
header('Content-Type: application/json');	

if(isset($_SESSION['user'])){
  	$_SESSION['uid']=null;
    $_SESSION['user']=null;
    session_destroy();
    $status = 200;
 	$msg = "Logged Out";    
  	
} else {
	$status = 400;
	$msg = "Already Logged Out";
}

$ret = array();
$ret['status'] = $status;
$ret['message'] = $msg;
echo json_encode($ret, JSON_PRETTY_PRINT);

?>