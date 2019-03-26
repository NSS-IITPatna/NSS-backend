<?php 
//Email Credentials
ini_set( "display_errors", 0);
require('resources/PHPMailer/PHPMailerAutoload.php');
define ('MAIL_HOST' ,'ssl://smtp.gmail.com');       //smtp server name
define ('MAIL_SMTP_AUTH' ,true);
define ('MAIL_USERNAME' ,'');
define ('MAIL_PASSWORD' ,'');
define ('MAIL_PORT' ,465);
	function mailTo($to, $subject='', $message='', $altmsg='') {
       // echo !extension_loaded('openssl')?"Not Available":"Available";
        $sender_email = "nss_gen_sec@iitp.ac.in";
        $sender_name = "Blood_request";
        try{
            $mail = new PHPMailer();
            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->Mailer = "smtp";
            $mail->Host = MAIL_HOST;
            $mail->Port = MAIL_PORT;
            $mail->SMTPAuth = true; // turn on SMTP authentication
            $mail->Username = MAIL_USERNAME; // SMTP username
            $mail->Password = MAIL_PASSWORD; // SMTP password

            $mail->setFrom($sender_email, $sender_name);
            $mail->AddAddress($to);

            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->AltBody = $altmsg;
            $mail->Send();
           // echo $message;
            return 1;
        } catch (Exception $e){
            return 0;
        }

// 		$headers = "From: $sender_name <$sender_email>"."\r\n".'X-Mailer: PHP/' . phpversion()."\r\n";
// 		$headers .= 'Content-type: text/html;charset=ISO-8859-1'."\r\n";
// 		$headers .= 'MIME-Version: 1.0'."\r\n\r\n";
// 		$isSuccess = mail($to,$subject,$message,$headers);
// 		if($isSuccess)
// 			return 1;
	}

?>
