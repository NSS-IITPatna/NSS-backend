# NSS-backend

###API details:

/Request blood:
* Post:
  *  Name
  *  Email
  *  ID
  *  Email
  *  Phone
  *  Reciever
  *  Address	

* JSON Response:
  {__
	status: // Status code returned by the api.__
	mail: // If the mail has been sent and/or recorded in the database.__
	message: //Any success or error message described here.__
  }
  

###Status codes:  
  * 200 : successful
  * 500 : DB connect error
  * 400 : bad request error
  * description of error is in the "message of the JSON object"
  
