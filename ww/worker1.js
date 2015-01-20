
importScripts('commJS.js'); 

function postMsgToMain(cmd, cmdStatus, oData)
{
	
	var msgObj = {};
	msgObj.type = cmd;
	msgObj.cmdStatus = cmdStatus;
	var d = new Date();   
    msgObj.respTime = "[" + d.toLocaleString() +"] ";
	msgObj.data = oData;
	self.postMessage(msgObj);
		
} // end of postMsgToMain()

// get messages from the main script
//  determine what is passed, an object, which has type,....
self.onmessage = function(e) {
       
       
//    var evt = new CustomEvent('printerstatechanged', { detail: "bcmWW", bubbles:true });

//    window.dispatchEvent(evt);
///////////////    console.log("WW got it");
	switch (e.data.type)
	{
 
		case "SEND":
				reqServerValues(e.data);
			
		  break;

		case "TEST":
				testResponse(e.data.payload);

		  break;
		  		  
		default:
				postMsgToMain("UNKNOWNCMD", "ERR" ,"yuck");
	} 

 

    
}// end of self.onmessage()


function reqServerValues(msg)
{
	
	// debugging   testResponse("enter dragon");
	
	// send the request to server
	// setup callback handler....
	// check if post back to main exists
	// if dosent exist, dont do anything
	if (typeof makeAJAXRequest == 'function') { 
// dna nov 15, orig and works		makeAJAXRequest(xhrResp, msg ); 
				makeAJAXRequest(xhrResp, msg );
	}else{
		// function dosent exist, so log....
	  	var errMsg = {};

		errMsg["msgType"] = "err";
		errMsg["id"] = "-1";
		errMsg["desc"] = "function does not exist: makeAJAXRequest()";

		postMsgToMain("ERR", "err" , errMsg);
	}

} // end of requestJackpotValues()



 
	//  this parsex an xml, looking for specific tags		
	function xhrResp(textResp) {
		// send the request to server
		var respMsg = {};

        var textRespObj = JSON.parse(textResp);

		respMsg["msgType"] = "mSvr";
		respMsg["id"] = "86";
		respMsg["pload"] = textResp; // textRespObj.response; // "return from server ";

		postMsgToMain("SVRM", "OK" , respMsg);						
				
	}  // end of xhrResp()
		
		

function testResponse(someString)
{
	// send the request to server
	var testMsg = {};

	testMsg["msgType"] = "bcm";
	testMsg["id"] = "15";
	testMsg["desc"] = "this is a string/obj [" + someString + "]";

	postMsgToMain("TEST", "OK" , testMsg);
	
} // end of requestJackpotValues()
