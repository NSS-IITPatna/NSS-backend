<?php

/**
 * 
 */
class User //extends AnotherClass
{
	/**
	  *	User Id
	  * @var int
	  */
	private $uid;

	/**
	  *	Name of user
	  * @var string
	  */
	private $name;

	/**
	  *	Email Id of user
	  * @var string
	  */
	private $email;

	/**
	  *	roll_no of the user to be displayed on-screen
	  * @var string
	  */
	private $roll_no;

    /**
      * cell the user works under
      * @var string
      */
    private $cell;


	/**
	  *	Access Level of user
	  * 0 - Member level access
	  * 1 - Admin level access 
	  * @var int
	  */
	private $access_level;

	/**
  	  * MySQL object - $conn - variable containing conection details
 	  * @var MySQLi	Object
  	  */	
	private $conn;
	

/** ==============================================
				ACCESSORS AND MODIFIERS
	==============================================*/

	/**
	  * All these modifiers set the private variables of the User object after validation check
	  * @param value needed to be set
	  * @return 0 : invalid parameter
	  * 		1 : Valid parameter and value set 
	  */

	function setUid($uid)
	{
		$this->uid = (int)$uid;
	}

	function setName($name)
	{
		if(preg_match('/^[a-zA-Z0-9.\s]*$/', $name)){
			$this->name = $name;
			return 1;
		} else {
			return 0;
		}
	}

	function setEmail($email)
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL)){
			$this->email = $email;
			return 1;
		} else {
			return 0;
		}
	}

	function setRollNo($roll_no)
	{
		if(preg_match('/^[1-9]{1}[0-9]{3}[a-zA-Z]{2}[0-9]{2}$/', $roll_no)){
			$this->roll_no = $roll_no;
			return 1;
		} else {
			return 0;
		}
	}

    function setCell($cell)
    {
        if(preg_match('/^[a-zA-Z_\s]*$/', $cell)){
            $this->cell = $cell;
            return 1;
        } else {
            return 0;
        }
    }

	function setAccessLevel($access_level)
	{
		if( in_array((int)$access_level, array(0,1,2)) ){
			$this->access_level = (int)$access_level;
			return 1;
		} else {
			return 0;
		}
	}

	function getUid()
	{
		return $this->uid;
	}

	function getName()
	{
		return $this->name;
	}	

	function getRollNo()
	{
		return $this->roll_no;
	}	

    function getCell()
    {
        return $this->cell;
    }

	/**
	  * This returns the access level of the User object
	  *	@return Access Level of the user
	  */
	function getAccessLevel(){
		return $this->access_level;
	}

/** ==============================================
			CONSTRUCTORS AND DESTRUCTORS
	==============================================*/

	function __construct($conn)
	{
		$this->uid = null;
		$this->name = null;
		$this->email = null;
		$this->roll_no = null;
        $this->cell = null;
		$this->access_level = null;
		$this->conn = $conn;		
	}

/** ==============================================
					METHODS
	==============================================*/

	/**
	  *	Checks if any detail/variable is left undefined
	  * @return 0 : error - If atleast one variable left undefined
	  *			1 : Success - All variables checked and defined
	  */
	function validateDetails($password)
	{
		if ($password=='' || $this->name==null || $this->email==null || $this->roll_no==null || $this->cell = null || !in_array((int)$this->access_level, array(0,1,2))) {
			return 0;
		}

		return 1;
	}

	/**
	  *	register user to the database
	  * @param $password - string - password given by the user
	  * @return array - array of details about the user
	  * 		['status'] : 200 : successfully added to database
	  *					   : 400 : Invalid credentials
	  *					   : 401 : Email Id already used
 	  *					   : 501 : Server Error
	  * 		['message'] : Message corresponding the status code 
	  */
	function registerUser($password)
	{
        $ret = array();
		if($this->validateDetails($password)) {
			$sql = "INSERT INTO `users` "
            	. "(`name`, `email`, `password`, `roll_no`, `cell`, `access_level`) "
            	. "VALUES "
            	. "( '" .$this->name."', '".$this->email."', '".sha1($password)."', '".$this->roll_no."', ". $this->cell .", ".$this->access_level. " )";

        	$resultObj = $this->conn->query($sql);
    
    		if ($this->conn->affected_rows > 0) {
    		 	$status = 200;
    		 	$msg =  "Saved to DB. Sending Verification"; 
    		} elseif ($this->conn->affected_rows==-1) {
     			if ($this->conn->errno==1062) {
        			$status = 402;
        			$msg = "Duplicate entry! Email Id already in use...";
      			} else {
        			$status = 501;
        			$msg = "Server error! Couldnot fetch result!";
      			}
    		} else {
     	 		$status = 501;
      			$msg = "Server error! Couldnot fetch result!";
   		 	}
   		} else {
   			$msg = "Invalid credentials!";
   			$status = 400;
   		}
    
    	$ret['status'] = $status;
    	$ret['message'] = $msg;
    	//$ret['sql'] = $sql;
    	return $ret;
	}	

	/**
	  *	gets user details of user with ID = $id
	  * @param $id - int - User Id of user
	  * @return array - array of details about the user
	  * 		['status'] : 200 : user details found
	  *					   : 400 : Invalid Id
	  *					   : 501 : Server Error
	  * 		['message'] : Message corresponding the status code 
	  * 		['result'] : ['uid, 'name', 'email', 'password', 'roll_no', 'access_level'] 
	  */
	function getUser($roll_no)
	{
        if (isset($roll_no)) {
            $sql = "SELECT * FROM users WHERE roll_no = '". $roll_no ."'";    
        } else {
            $sql = "SELECT * FROM users WHERE roll_no = '-1'";
        }
		$result = $this->conn->query($sql);
        if(!$result || $result->num_rows!=1){
            //$errorH = alog("getuser error: numrows : ". $result->num_rows ."error:". $this->conn->error);
            $error = "Error in displaying result for given User ID. ";
            error_log("Error: ". $result->num_rows ." - ". $this->conn->error);
            $status = 501;
            $msg = $error." Or User doesnt Exist!";
        } else {
        	$user_details = $result->fetch_assoc();
        	if(isset($user_details)) {
        		$status = 200;
        		$res = $user_details;
        		$this->setAll($res);
        	} else {
        		$status = 400;
        		$msg = "Invalid Id";
        	}
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
	  *	converts given array object to User object (Sets corrsoponding values to current User Object)
	  * @param $arr - array - containing user info
	  */
    function setAll($arr)
    {
    	if (!empty($arr)) {
      		isset($arr['uid']) ? $this->setUid($arr['uid']) : '';
      		isset($arr['name']) ? $this->setName($arr['name']) : '';
      		isset($arr['email']) ? $this->setEmail($arr['email']) : '';
      		isset($arr['roll_no']) ? $this->setRollNo($arr['roll_no']) : '';
      		isset($arr['cell']) ? $this->setCell($arr['cell']) : '';
            isset($arr['access_level']) ? $this->setAccessLevel($arr['access_level']) : $this->setAccessLevel(0);   
    	}
    }

    /**
	  *	converts current User object to array (Sets corrsoponding values to array)
	  * @return $arr - array - containing user info
	  */
    function userToArray() 
    {
    	$arr = array();
    	if ($this->uid!=null) {
    		$arr['uid'] = $this->uid;
    	}
    	if ($this->name!=null) {
    		$arr['name'] = $this->name;
    	}
    	if ($this->email!=null) {
    		$arr['email'] = $this->email;
    	}
    	if ($this->roll_no!=null) {
    		$arr['roll_no'] = $this->roll_no;
    	}
    	if ($this->cell!=null) {
            $arr['cell'] = $this->cell;
        }
        if (in_array((int)$this->access_level, array(0,1,2))) {
    		$arr['access_level'] = $this->access_level;
    	}

    	return $arr;
    }

    /**
	  *	Checks if the roll_no enterd is already in use
	  * @param $roll_no - string - roll_no to be used/checked
	  * @return int : -1 : error
	  * 			:  0 : roll_no already in use
	  *				:  1 : roll_no not in use => can be used
	  */
    function checkroll_no($roll_no)
    {
    	$sql = "SELECT roll_no FROM users";
       	$result = $this->conn->query($sql);
        if(!$result || $result->num_rows<=0){
            $errorH = alog("getuser error: numrows : ". $result->num_rows ."error:". $this->conn->error);
            $error = "Error in displaying result for given User ID. Err no: #".$errorH;
            error_log("Error: ".$errorH .": ". $result->num_rows ." - ". $this->conn->error);
            return -1;
        } else {
        	while($users = $result->fetch_assoc()){
        		if($users['roll_no']==$roll_no){
        			return 0;
        		}
        	}
        	$result->free();
        	return 1;
        }
    }

    /**
	  *	Checks if parameter is roll_no or email
	  * @param $in - string - roll_no or email input to be checked
	  * @return int : -1 : none of these
	  * 			:  0 : Email
	  *				:  1 : roll_no
	  */
    private function MailOrRollNo($in)
    {
    	if($this->setEmail($in)) {
    		return 0;
    	} 
    	if($this->setRollNo($in)) {
    		return 1;
    	}

    	return -1;
    }

    function loginUser($inp, $password) 
    {
    	$status = 0;
    	$res = null;
    	$msg = "";
    	$check = $this->MailOrRollNo($inp);
    	if ($check==0) {
    		$sql = "SELECT * FROM users WHERE email='". $this->email. "'";
    	} elseif ($check==1) {
    		$sql = "SELECT * FROM users WHERE roll_no='". $this->roll_no. "'";
    	} else {
    		$status = 400;
    		$msg = "Email or Roll No. invalid!";
    	}
    	
    	if ($status!=400) {
       		$result = ($this->conn)->query($sql);
       		if(!$result || $result->num_rows<=0){
        	    //$errorH = alog("getuser error: numrows : ". $result->num_rows ."error:". $this->conn->error);
        	    $error = "Error in displaying result ";
        	    error_log("Error: ". $error .": ". $result->num_rows ." - ". $this->conn->error);
                $status = 501;
        	    $msg = $error;
        	} else {
        		$user_details = $result->fetch_assoc();
        		if (!isset($user_details)) {
        			$status = 501;
        			$msg = "Could not fetch result!!";
        		} else {
        			if($user_details['password']==sha1($password)){
        				$status = 200;
        				$res = $user_details;
        			} else {
        				$status = 400;
        				$msg = "Password or roll_no/email incorrect";
        			}
        		}
        		$result->free();
        	}
    	} 

    	$ret = array();
    	$ret['status'] = $status;
    	if ($status==200) {
    		$ret['result'] = $res;
    		$this->setAll($res);
    	} else {
    		$ret['message'] = $msg;
    	}

    	return $ret;
    }

}

?>
