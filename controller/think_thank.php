<?php

include 'dbConfig.php';
include 'emailCredential.php';
header('Content-Type: application/json');	

function SQLInjFilter(&$unfilteredString){
		$unfilteredString = mb_convert_encoding($unfilteredString, 'UTF-8', 'UTF-8');
		$unfilteredString = htmlentities($unfilteredString, ENT_QUOTES, 'UTF-8');
		// return $unfilteredString;
}

$_POST['name'] = "Deepanjan Datta";
$_POST['email'] = "deepanjan052000@gmail.com";
$_POST['idea'] = "Fuck off my friend!";
$_POST['code'] = "1";

$error = "";
$return = "";
$code = (string)$_POST['code'];
$status = 0;
$mail_msg = "";
$ret = array();


if (!isset($_POST['name']) || $_POST['name']=="") {
	$error .= "Name invalid. ";
	$status = 400;
}
if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
	$error .= "Email-ID invalid. ";
	$status = 400;
}
if (!isset($_POST['idea']) && $_POST['idea']=="") {
	$error .= "Idea blank. ";
	$status = 400;
}
if (!isset($_POST['code']) && $_POST['code']=="") {
	$error .= "Invalid input!!";
	$status = 400;
}

if($status!=400){
	//sql injection filter function call goes here
	SQLInjFilter($_POST['phone']);
	SQLInjFilter($_POST['email']);
	SQLInjFilter($_POST['idea']);
	$table = "think";
	$sql = "INSERT INTO `". $table ."`(name,email,idea) VALUES ('".$_POST['name']."', '".$_POST['email']."', '". $_POST['idea'] . "')";

	if($code=="1"){
		$table = "thank";	
		$sql = "INSERT INTO `". $table ."`(name,email,message) VALUES ('".$_POST['name']."', '".$_POST['email']."', '". $_POST['idea'] . "')";
	}
	
	//db stuff here
	
	if($link =mysqli_connect($servername, $username, $password, $dbname)){
		$result = mysqli_query($link,$sql);
	    if($result){
	    	$status=200;
	    	$return="Successfully Submitted";
	    	
	    	//=========== Mail Data ====================
	    	$mail_to = "adarsh.cs17@iitp.ac.in";
	    	if($code=="1"){
	    		$subject = "Thank message by ". $_POST['name'];
				$mail_body = "Name : ". $_POST['name'] . "<br> Email: ". $_POST['email'] . "<br> Message: " . $_POST['idea'];
	    		$alternate_msg = "Name : ". $_POST['name'] . " | Email: ". $_POST['email'] . " | Message: " . $_POST['idea'];
	    	}else{
	    		$subject = "Idea Submitted by ". $_POST['name'];
				$mail_body = "Name : ". $_POST['name'] . "<br> Email: ". $_POST['email'] . "<br> Idea: " . $_POST['idea'];
	    		$alternate_msg = "Name : ". $_POST['name'] . " | Email: ". $_POST['email'] . " | Idea: " . $_POST['idea'];		
	    	}
	    	if(mailTo($mail_to, $subject, $mail_body, $alternate_msg)){
	    		$mail_msg = "sent";
	    		$sql = "UPDATE `". $table ."` SET mail_sent=2 WHERE `name`= '" .$_POST['name']. "' AND email= '" .$_POST['email']. "' AND mail_sent=1";
        		if($link =mysqli_connect($servername, $username, $password, $dbname)){
        			$result = mysqli_query($link,$sql); $id=0;
            		if($result || mysqli_num_rows($result)>0){}
            		else{
	    				$mail_msg.= " and not saved in db";
	    			}
	    		}else{
	    			$mail_msg.= " and not saved in db";
	    		}
	    	}else{
	    		$mail_msg = "not sent";
	    		$sql = "UPDATE `". $table ."` SET mail_sent=0 WHERE `name`= '" .$_POST['name']. "' AND email= '" .$_POST['email']. "' AND mail_sent=1";
        		if($link =mysqli_connect($servername, $username, $password, $dbname)){
        			$result = mysqli_query($link,$sql); $id=0;
            		if($result || mysqli_num_rows($result)>0){}
            		else{
	    				$mail_msg.= " and error not saved in db";
	    			}
	    		}else{
	    			$mail_msg.= " and error not saved in db";
	    		}
	    	}
	    } else {
	    	//error to fetch result
	    	$status = 400;
	    	$error = "error to fetch result ".mysqli_errno($link);
		}
    }else{
    	//error to connect to db
    	$status = 500;
    	$error = "error connecting to DB";
		$error.= "Debugging errno: " . mysqli_connect_errno();
	}
}
//  $status=200;
// 	$return="Successfully Registered";
if($status == 200){
	$ret["status"] = 200;
	$ret["message"] = $return;
	$ret["mail"] = $mail_msg;
}else{
	$ret["status"] = $status;
	$ret["message"] = $error;
	$ret["mail"] = $mail_msg;
	//errorLog($error);
}
//$ret['deb']=$_POST['deb'];
//$data_back = json_decode(file_get_contents('php://input'));
//echo $data_back->{"data1"};
//echo var_dump($obj);
//http_response_code($status);
echo json_encode($ret, JSON_PRETTY_PRINT);

?>
