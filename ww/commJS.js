


var callback;

// log, post a message to main thread
// could be used for sending messages to main script or for debugging
function wwLog(logLevel, oData){
	
	// check if post back to main exists
	// if dosent exist, dont do anything
	if (typeof postMsgToMain == 'function') { 
		postMsgToMain(logLevel, "INFO" ,oData); 
	}


} // end of wwLog()


///// WIP  improved /////////////////




/**********************************************************
function    : createRequestObject
description :  
***********************************************************/
function createRequestObject() {
    var tmpXmlHttpObject;
     
    // firefox, chrome, Opera, Safari, IE7+,, would use this method ...
    tmpXmlHttpObject = new XMLHttpRequest();
	
    return tmpXmlHttpObject;

}// end of function createRequestObject() 


//call the above function to create the XMLHttpRequest object
var myHttpObj = createRequestObject();


/**********************************************************
function    : makeRequestII
description :  
***********************************************************/
function makeAJAXRequest(clientRespHandler, msgReqObj ) {

    var postParams = null;
    var urlHandler = null;
//////    console.log("makeAJAXRequest");
////    console.log("DEBUG" + ' making req: ' + msgReqObj.reqType);
    
    callback = clientRespHandler;
    
    //assign a handler for the response
    myHttpObj.onreadystatechange = checkState;

 //    urlHandler = msgReqObj.pageUrl;
    if (msgReqObj.reqType.toUpperCase() == "POST") {
      urlHandler =  msgReqObj.pageUrl; 
      postParams = msgReqObj.reqParam;  
    }
    else {
      urlHandler = msgReqObj.pageUrl+'?'+msgReqObj.reqParam; 
    }
    
    //make a connection to the server ... specifying that you intend to make a GET request 
    //to the server. Specifiy the page name and the URL parameters to send
	 myHttpObj.open(msgReqObj.reqType, urlHandler, true);
	 
	 myHttpObj.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	 
    //actually send the request to the server
     myHttpObj.send(postParams);
    
    
} // end of function makeRequestII()



function checkState(){
	// http://stackoverflow.com/questions/15622985/why-does-this-piece-of-js-throw-a-dom-exception
	// http://stackoverflow.com/questions/15398571/uncaught-error-invalidstateerror-dom-exception-11-with-ajax
	if (myHttpObj.readyState == 4){
		if (myHttpObj.status == 200){
			callback(myHttpObj.responseText); // displayLocation(http_request.responseText);
		} else {
			//   alert('Bad Request: '+myHttpObj.status);
			wwLog("DEBUG", 'checkState status: ' + myHttpObj.status);
		}
	}

} // end of function checkState()

