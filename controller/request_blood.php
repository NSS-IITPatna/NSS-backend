<?php
include 'dbConfig.php';
include 'emailCredential.php';
function SQLInjFilter(&$unfilteredString){
		$unfilteredString = mb_convert_encoding($unfilteredString, 'UTF-8', 'UTF-8');
		$unfilteredString = htmlentities($unfilteredString, ENT_QUOTES, 'UTF-8');
		// return $unfilteredString;
}

$error = "";
$return = "";
$id = $_POST['id'];
$status = 0;
$ret = array();

if (!isset($_POST['name']) || $_POST['name']=="") {
	$error .= "Name invalid. ";
	$status = 400;
}
if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
	$error .= "Email-ID invalid. ";
	$status = 400;
}
if (!isset($_POST['phone']) || !preg_match('/^[6789][0-9]{9}$/',$_POST['phone'])) {//simple regex for mobile validation
	$error .= "Phone No. Invalid. ";
	$status = 400;
}
if (!isset($_POST['address']) || $_POST['address']=="") {
	$error .= "Address blank. ";
	$status = 400;
}
if (!isset($_POST['reciever']) && $_POST['reciever']=="") {
	$error .= "Reciever name blank. ";
	$status = 400;
}
if (isset($_POST['id']) && $_POST['id']=="") {
	$id = "Nil";	
}

//if (!isset($_POST['year']) || $_POST['year']<1 || $_POST['year']>4) {
//	$error .= "Year invalid. ";
//	$status = 400;
//}
if($status!=400){
	//sql injection filter function call goes here
	SQLInjFilter($_POST['phone']);
	SQLInjFilter($_POST['email']);
	SQLInjFilter($_POST['name']);
	SQLInjFilter($_POST['reciever']);
	SQLInjFilter($_POST['id']);
	SQLInjFilter($_POST['address']);

	//db stuff here
	$sql = "INSERT INTO `blood_request`(name,email,phone,reciever,id,address) VALUES ('".$_POST['name']."', '".$_POST['email']."', '".$_POST['phone']."', '". $_POST['reciever'] ."', '". $id ."', '". $_POST['address'] . "')";

	if($link =mysqli_connect($servername, $username, $password, $dbname)){
		$result = mysqli_query($link,$sql);
	    if($result){
	    	$status=200;
	    	$return="Request Successfully Registered";
	    	
	    	//=========== Mail Data ====================
	    	$mail_to = "nss_gen_sec@iitp.ac.in";
	    	$subject = "Blood Request by ". $_POST['name'];
	    	$mail_body = "Name : ". $_POST['name'] . "(ID: " . $id . ")<br> Email: ". $_POST['email'] . "<br> Phone: " . $_POST['phone'] . "<br> Reciever Name: ". $_POST['reciever'] ."<br> Address: ". $_POST['address'];
	    	$alternate_msg = "Name : ". $_POST['name'] . "(ID: " . $id . ") | Email: ". $_POST['email'] . " | Phone: " . $_POST['phone'] . " | Reciever Name: ". $_POST['reciever'] ." | Address: ". $_POST['address'];
	    	if(mailTo($mail_to, $subject, $mail_body, $alternate_msg)){
	    		$ret["mail"] = "sent";
	    		$sql = "UPDATE `blood_request` SET mail_sent=2 WHERE `name`= '" .$_POST['name']. "' AND email= '" .$_POST['email']. "' AND reciever='". $_POST['reciever']. "' AND given=0 AND mail_sent=1";
        		if($link =mysqli_connect($servername, $username, $password, $dbname)){
        			$result = mysqli_query($link,$sql); $id=0;
            		if($result || mysqli_num_rows($result)>0){}
            		else{
	    				$ret['mail'].= " and not saved in db";
	    			}
	    		}else{
	    			$ret['mail'].= " and not saved in db";
	    		}
	    	}else{
	    		$ret["mail"] = "not sent";
	    		$sql = "UPDATE `blood_request` SET mail_sent=0 WHERE `name`= '" .$_POST['name']. "' AND email= '" .$_POST['email']. "' AND reciever='". $_POST['reciever']. "' AND given=0 AND mail_sent=1";
        		if($link =mysqli_connect($servername, $username, $password, $dbname)){
        			$result = mysqli_query($link,$sql); $id=0;
            		if($result || mysqli_num_rows($result)>0){}
            		else{
	    				$ret['mail'].= " and error not saved in db";
	    			}
	    		}else{
	    			$ret['mail'].= " and error not saved in db";
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
		$error.=   "Debugging errno: " . mysqli_connect_errno();
	}
}
//  $status=200;
// 	$return="Successfully Registered";
if($status == 200){
	$ret["status"] = 200;
	$ret["message"] = $return;
}else{
	$ret["status"] = $status;
	$ret["message"] = $error." For help, error reference no: ";
	//errorLog($error);
}
//$ret['deb']=$_POST['deb'];
//$data_back = json_decode(file_get_contents('php://input'));
//echo $data_back->{"data1"};
//echo var_dump($obj);
//http_response_code($status);
echo json_encode($ret);

?>
