 
	
	// see: http://www.htmlgoodies.com/beyond/javascript/article.php/3724571/Using-Multiple-JavaScript-Onload-Functions.htm
	//
	//
	function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}	 

// session storage supported?
var ss_supported = false;


		// NOTE: sessionStorage works with "string" value, so object has to be stringified
		function getFromSS(key) { 
			
			if (sessionStorage.getItem(key)){
				console.log("session storage length: " + sessionStorage.length);
				// Retrieve the item
	//			return sessionStorage.getItem(key);
				return JSON.parse(sessionStorage.getItem(key));
			}

		} //end of getFromSS()	

		function saveInSS(key, value) { 
			
			try {
	//			sessionStorage.setItem(key, value);
				sessionStorage.setItem(key, JSON.stringify(value));
				console.log("session storage save: SAVED!" );
			} catch (e) {
				if (e == QUOTA_EXCEEDED_ERR) {
					alert('Quota exceeded!');
					console.log("session storage save: Quota exceeded!" );
				}
			}

		} //end of saveInSS()	

		function delSS(key) { 
			
			sessionStorage.removeItem(key);
			console.log("local storage length: item removed");

		} //end of delSS()	





		//  web worker stuff
		function supports_web_workers() {
		  return !!window.Worker;
		}
		

		function coreInit() {
						
			if('sessionStorage' in window && window['sessionStorage'] !== null) {
				ss_supported = true;
			} else{
				ss_supported = false;
			}
			console.log("sessionStorage support: " + ss_supported);
			
			
			var ww_supp = supports_web_workers();

			if (ww_supp){
				 console.log("core: create ww");
				// create the web workers
				bmWorker = new Worker("ww/worker1.js");// to get working
						
				// get messages from the web workers
				bmWorker.addEventListener('message', tooHandler, false);

			}else{
			   // notify that browser supporting web worker is needed/update to latest
			}

		}// end of function coreInit()




		// TODO:  figure out what to do with this	
		function addtext(newtext) {
			var d = new Date();   
            var dateString = "[" + d.toLocaleString() +"] ";
			
//			var e = document.getElementById('my_text');
//			e.value += dateString + " " + newtext + "\n";
//			e.scrollTop =    e.scrollHeight  // to put the scroll bar at the bottom, to show latest entry
		}



		function tooHandler(e){


					switch (e.data.type)
					{
						case "INITREQ":

						  break;

						case "KICK":

						  break;
						  
						case "SVRM":
						
						         var jsonData = JSON.parse(e.data.data.pload);
		//				    var evt = new CustomEvent('printerstatechanged', { detail: "bcm" });
        //                                window.dispatchEvent(evt);
                                        
								 //Dispatch an event
								var evt = document.createEvent("CustomEvent");
								evt.initCustomEvent(jsonData.event, true, true, jsonData.response);
								window.dispatchEvent(evt);                                       
                                        
                                        console.log(" returned: " + jsonData.event);
								var data = jsonData.response;

						  break;

						case "MONITOR":

						  break;

						case "TEST":
								var j = 0;
								addtext(e.data.respTime + " " + e.data.data.desc );
						  break;
						  
						case "DEBUG":
								addtext(e.data.type + " " + e.data.cmdStatus + " " + e.data.data);
						  break;
						  
						case "ERR":
								addtext(e.data.respTime + " " + e.data.data.desc );
						  break;
						  														  
						default:
								addtext(e.data.type + " ******* UNKNOWN CMD " + e.data.cmdStatus +  "OK" ," " + e.data.data );
					} 

		} // end of function tooHandler()	


 		function makePageURL(folder, svr_code)
		{
		
            var splitUrl = window.location.pathname.substring(1).split("/");
            // remove last entry, the page
            var urlPage = splitUrl.splice(splitUrl.length-1, 1);

			return window.location.origin + "/" + splitUrl.join("/") +"/"+folder + "/" + svr_code;
			
			
		} // end of function makePageURL()
		

	


 		function buildSendMsg(type, paramObj, url, reqType){
			var msgObj = {};
			msgObj.type = type; // "SEND";
			msgObj.reqParam = 'msgReq=' + paramObj; // 'msgReq={"resource":"APPNT","msgtype":"GETALL", "aparam1":"joey", "aparam2":"bcm"}';
			msgObj.pageUrl = url; // makePageURL("svr", "tstMsgSrvr.php");
			msgObj.reqType = reqType; // "post";
			
			bmWorker.postMessage(msgObj);	


			
		} // end of function buildSendMsg()

	
		//Function To Display Popup
		function div_show(popupDiv) {
//			document.getElementById(popupDiv).style.display = "block";
		}
		//Function to Hide Popup
		function div_hide(popupDiv){
         document.getElementById(popupDiv).style.display = "none";
		}





addLoadEvent(coreInit);


		
	

								

				

