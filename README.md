# NSS-backend

###API details:

api/?Request blood:
* POST:
  *  Name
  *  Email
  *  ID
  *  Email
  *  Phone
  *  Reciever
  *  Address	

* JSON Response: <br/>
  { <br/>
	status: // Status code returned by the api. <br/>
	mail: // If the mail has been sent and/or recorded in the database. <br/>
	message: //Any success or error message described here. <br/>
  }
  

###Status codes:  
  * 200 : successful
  * 500 : DB connect error
  * 501 : Could not fetcfh data from DB
  * 502 : Could not update/add/remove data in DB
  * 400 : bad request error/ Invalid Inputs
  * 402 : Duplicate Entry 
  * 403 : Access Forbidden/ Unauthorised Access
  * 404 : API Not Found
  * description of error is in the "message of the JSON object"
  
