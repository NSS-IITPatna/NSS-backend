<?php

require_once("user.php");

/**
 * 
 */
class Hours //extends AnotherClass
{
	/**
	  *	User object of logged In user
	  * @var int
	  */
	private $thisUser;

	/**
	  *	Array of User Ids of followers of the logged in user
	  * @var int
	  */
	private $hour_details;

	private $hours;

	/**
  	  * MySQL object - $conn - variable containing conection details
 	  * @var MySQLi	Object
  	  */	
	private $conn;
	

/** ==============================================
				ACCESSORS AND MODIFIERS
	==============================================*/

	function change_thisUser($id)
	{
		$user = new User($this->conn);
		$user->arrayToUser($user->getUser($id));
		$this->thisUser = $user;
	} 

	function setHours($arr)
    {
    	if (!empty($arr)) {
      		isset($arr['hours']) ? $this->addHours($arr['hours']) : '';
      		if(isset($arr['reason']) && isset($arr['hours']) && isset($arr['date'])){
      			$this->setHourDetails($arr['hours'],$arr['reason'], $arr['date']);
      		}
      	}
    }

    function addHours($hours){
    	$this->hours+=(int)$hours;
    }

	function setHourDetails($hours, $reason, $date){
		$details = array();
		$details['hours'] = (int)$hours;
		$details['reason'] = $reason;
		$details['date'] = $date;
		
		$this->hour_details[] = $details;
	}

/** ==============================================
			CONSTRUCTORS AND DESTRUCTORS
	==============================================*/

	function __construct($conn, User $thisUser)
	{
		$this->thisUser = $thisUser;
		$this->conn = $conn;
		$this->hour_details = array();
		$this->hours = 0;	
	}

/** ==============================================
					METHODS
	==============================================*/

	/**
	  *	gets array of all users following the loggedin user
	  * @return array of all hour entries of the loggedin user
	  */
	function getAllHourDetailsOfLoggedIn()
	{
		$sql = "SELECT * FROM hours WHERE uid = '".$this->thisUser->getUid(). "' ORDER BY date DESC";

		$result = $this->conn->query($sql);
        if(!$result || $result->num_rows<=0){
            $errorH = alog("getuser error: numrows : ". $result->num_rows ."error:". $this->conn->error);
            $error = "Error in displaying result for given User ID. Err no: #".$errorH;
            error_log("Error: ".$errorH .": ". $result->num_rows ." - ". $this->conn->error);
            $status = 501;
            $msg = $error;
        } else {
        	$res = array();
        	while ($hours = $result->fetch_assoc()) {
        		$status = 200;
        		$res[] = $hours;
        	}
        	$this->setHours($res);
        	$result->free();
        }

        $ret = array();
        $ret['status'] = $status;
        if($status==200){
        	$ret['result'] = $res;
        } else {
        	$ret['message'] = $msg;
        }
        return $ret;		
	}

	/**
	  *	gets array of all users hours details 
	  * @return array containing arrays of different users hour details grouped by user ids 
	  */
	function getAllUsersHourDetails()
	{
		if(!isset($this->thisUser) || $this->thisUser->getAccessLevel()!=1){
			$status = 403;
			$msg = "Access Forbidden";
		} else {
			$sql = "SELECT * FROM hours ORDER BY uid , date Desc";

			$result = $this->conn->query($sql);
	        if(!$result || $result->num_rows<=0){
	            $errorH = alog("getuser error: numrows : ". $result->num_rows ."error:". $this->conn->error);
	            $error = "Error in displaying result for given User ID. Err no: #".$errorH;
	            error_log("Error: ".$errorH .": ". $result->num_rows ." - ". $this->conn->error);
	            $status = 501;
	            $msg = $error;
	        } else {
	        	$status = 200;
	        	$res = array();
   		     	$uid = -1;
    	    	while ($details = $result->fetch_assoc()) {
    	    		if ($details['uid']!=$uid) {
    	    			$res[] = $temp;
    	    			$temp = array();
    	    		}
    	    		$temp[] = $details;
    	    	}
    	    	$result->free();
    	    }
    	}

        $ret = array();
        $ret['status'] = $status;
        if($status==200){
        	$ret['result'] = $res;
        } else {
        	$ret['message'] = $msg;
        }
        return $ret;		
	}

	/**
	  *	get latest hour detail of a user
	  * @param User $user : User who is to be followed by Logged in user
	  * @return 
	  */
	function getHourDetails(User $user)
	{
		if(!isset($this->thisUser) || $this->thisUser->getAccessLevel()!=1){
			$status = 403;
			$msg = "Access Forbidden";
		} else {
			$sql = "SELECT * FROM hours WHERE uid = '". $user->getUid(). "' ORDER BY date DESC";

			$result = $this->conn->query($sql);
        	if(!$result || $result->num_rows<=0){
        	    $errorH = alog("getuser error: numrows : ". $result->num_rows ."error:". $this->conn->error);
        	    $error = "Error in displaying result for given User ID. Err no: #".$errorH;
        	    error_log("Error: ".$errorH .": ". $result->num_rows ." - ". $this->conn->error);
        	    $status = 501;
        	    $msg = $error;
        	} else {
        		$res = array();
        		$status = 200;
        		while ($hours = $result->fetch_assoc()) {
        			$res[] = $hours;
        		}
        		$result->free();
        	}	
        }

        $ret = array();
        $ret['status'] = $status;
        if($status==200){
        	$ret['result'] = $res;
        } else {
        	$ret['message'] = $msg;
        }
        return $ret;		
	}

	/**
	  *	adds a user to 'following' list of loggedin User (Logged in users starts following this user (User $user))
	  * @param User $user : User who is to be followed by Logged in user
	  * @return ['status'] 	: 200 : Relationship established (added to database)
	  *					   	: 400 : Invalid Credentials/Already Follower of $user
	  *					   	: 501 : Server Error
	  * 		['message'] : Message corresponding the status code 
	  */
	function getTotalHour($arr)
	{
		$tot_hour = 0;
		foreach ($arr as $value) {
			$tot_hour+=(int)$value['hours'];
		}		

		return $tot_hour;
	}

	private function validate(User $user,$hours, $reason, $date)
	{
		if($user->getName()==null || $user->getUid()==null){
			return 0;
		}
		if((int)$hours>=24){
			return 0;
		}
		if (!preg_match('/^[a-zA-Z0-9._-()\s]*$/', $reason)) {
			return 0;
		}
		if (strtotime($date)-strtotime(Date())>0) {
			return 0;
		}

		return 1;
	}

	/**
	  *	adds Hours of a user 
	  * @param User $user : User who's hour is to be added
	  * @return ['status'] 	: 200 : added to database
	  *					   	: 400 : Invalid user/input
	  *						: 402 : Already Added
	  *						: 403 : Access Forbidden
	  *					   	: 501 : Server Error
	  * 		['message'] : Message corresponding the status code 
	  */
	function addNewHours(User $user, $hours, $reason, $date)
	{
		if(!isset($this->thisUser) || $this->thisUser->getAccessLevel()!=1){
			$status = 403;
			$msg = "Access Forbidden";
		} else {
			if($this->validate($user,$hours,$reason,$date)) {
				$sql = "INSERT INTO hours(uid,hours,reason,date,key) VALUES (".$user->getUid().", ".$hours.", ".$reason.", ".$date.", ".sha1($reason.$hour.$date).")";

				$result = $this->conn->query($sql);
        		if(!$result || $result->num_rows<=0){
        		    $errorH = alog("getuser error: numrows : ". $result->num_rows ."error:". $this->conn->error);
        		    $error = "Error in displaying result for given User ID. Err no: #".$errorH;
        		    error_log("Error: ".$errorH .": ". $result->num_rows ." - ". $this->conn->error);
        		    $status = 501;
        		    $msg = $error;
        		} else {
        			$res = array();
        			$status = 200;
        			$msg = "Added successfully"; 
        			$result->free();
        		}	
        	} else {
        		$status = 400;
				$msg = "Invalid input";
        	}
        } 

        $ret = array();
        $ret['status'] = $status;
        $ret['message'] = $msg;
        return $ret;		
	}

	/**
	  *	check if the user is being followed by loggedin User (Logged in users starts following this user (User $user))
	  * @param User $user : User who is to be followed by Logged in user
	  * @return -1 : process failed (Error)
	  * 		 1 : Logged In user followed by $user
	  *		 	 0 : Logged In user is not followed by $user
	  */
	
}
