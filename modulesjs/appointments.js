

////// start  module                   //////////////////////

var popupFormModule = (function () {
//
// from: http://www.sefol.com/?p=1090
//  

// nned a valid name?? instead of text??
//
  //Immediately returns an anonymous function which builds our modules
  return function (htmlForm, reqFormElemDef, appendTo, submitCallback, aTimeObj) {       
       var popupHeadWrap = '<div id="popupContact"><div class="fromm">';
       var closeImg = '<img id="close" src="img/3.png" >';
       var popupFootWrap = '</div></div>';
       
        var theForm = htmlForm;
        var requiredElems = reqFormElemDef;
        var parentDiv = appendTo;
        var callback = submitCallback;
        var setNum = 10;
        var timeUtils = aTimeObj;


      
        var attachForm = function(){
            document.getElementById(parentDiv).innerHTML = popupHeadWrap +closeImg+ theForm + popupFootWrap;
            document.getElementById('close').addEventListener('click',function(){ document.getElementById(parentDiv).style.display = "none";  });
            document.getElementById('submitform').addEventListener('click',function(){ console.log("hey");validateFormElems();  });
        }
 
  		//Function To Display Popup
		var initform = function() {
	         document.getElementById("formtype").value = "ADD";
	         document.getElementById("appntTitle").innerHTML = "Add appointment";
	         document.getElementById("name").value = "";
	         document.getElementById("name").readOnly = false;

            document.getElementById("email").value = "";
		    document.getElementById("date").value = "";
		    document.getElementById("time").value = "";
		}
		
 		//Function To Display Popup
		var show = function() {
			document.getElementById(parentDiv).style.display = "block";
		}
		//Function to Hide Popup
		var hide = function(){
			document.getElementById(parentDiv).style.display = "none";
		}
		
		var getFormValues =	function(requiredElems) {           
            var allvals = {};
            _.forEach(requiredElems , function(anObj) { 
                allvals[anObj.id] = document.getElementById(anObj.id).value ;
	        });
	
	        return allvals;
	        
        } // end of function getFormValues()
 
 
 
 		var setFormValues =	function(requiredElems) {           

            document.getElementById("formtype").value = "EDIT";
            document.getElementById("eid").value = requiredElems.id;
            document.getElementById("appntTitle").innerHTML = "Edit appointment";
            // shouldnt be able to change name, maybe just email address , but appointment yes!
            document.getElementById("name").value = requiredElems.name;
            document.getElementById("name").readOnly = true;

            document.getElementById("email").value = requiredElems.emailadr;
            var dateTimeArr = requiredElems.appointment.split(" ");
		    document.getElementById("date").value = dateTimeArr[0];
		    document.getElementById("time").value = timeUtils.convertTime24to12(dateTimeArr[1].substr(0,5));
	        
        } // end of function setFormValues()
 
 
 
 
 
 
 
 		//Function to Hide Popup
		var validateFormElems = function(){
			if(validateForm(requiredElems)){
    			callback.call(undefined);
            }
		}
     
        var validateForm = function(requiredElems){
	        var validators = {'EMAIL': vEmail, 'DATE': vDate, 'TIME': vTime, 'TEXT': vText };
	        
		     formValid = true;
		     // TODO: SCRUB DATA <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
			_.forEach(requiredElems , function(anObj) { 
				console.log("module - validateForm: " + anObj.id +"  " + anObj.type+ " " +anObj.errstr);

		          if(anObj.type !== "GRAB"){
					  if(!validators[anObj.type].call(undefined, anObj.id)){
							document.getElementById(anObj.id).value = "";
							document.getElementById(anObj.id).placeholder = anObj.errstr;
							document.getElementById(anObj.id).className += " formInvalid";
							formValid = false;			  
					  }
			      }	      
			});
			
			return formValid;
	
	    } // end of function validateForm()
     
        var vText = function(inText)
		{
		    var returnCode = true;  // true successful,  false not acceptable
		    var returnMsg  = "ok";  // descriptive string
		
		    var elemValue = document.getElementById(inText).value;
		
		    if (elemValue==null || elemValue=="")
		    {
			returnCode = false;
			returnMsg = "Required field.";
		    }
		      
		    return returnCode;  
		} // end of function _text()


		var vEmail = function(inText){
		    var elemValue = document.getElementById(inText).value;
			console.log("validate email: " + inText +"  "+ elemValue);
		    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		    return re.test(elemValue);
		}// end of function _email()
 
 		/**
		 * isValidDate(str)
		 * @param string str value yyyy-mm-dd
		 * @return boolean true or false
		 * IF date is valid return true
		 */
		var vDate = function(inText){
			var elemValue = document.getElementById(inText).value;
			console.log("validate date: " + inText +"  "+ elemValue);
			
			// STRING FORMAT yyyy-mm-dd
			if(elemValue=="" || elemValue==null){return false;}								
			
			// m[1] is year 'YYYY' * m[2] is month 'MM' * m[3] is day 'DD'					
			var m = elemValue.match(/(\d{4})-(\d{2})-(\d{2})/);
			
			// STR IS NOT FIT m IS NOT OBJECT
			if( m === null || typeof m !== 'object'){
				return false;
			}				
			
			// CHECK m TYPE
			if (typeof m !== 'object' && m !== null && m.size!==3){
				return false;
			}
						
			var ret = true;						
			var thisYear = new Date().getFullYear(); //YEAR NOW
			var minYear = 2013; //MIN YEAR
			
			// YEAR CHECK
			if( (m[1].length < 4) || m[1] < minYear || m[1] < thisYear){
				ret = false;
			}
			// MONTH CHECK			
			if( (m[2].length < 2) || m[2] < 1 || m[2] > 12){
				ret = false;
			}
			// DAY CHECK
			if( (m[3].length < 2) || m[3] < 1 || m[3] > 31){
				ret = false;
			}
			
			return ret;			
		} // end of _date()
 
 
 		var vTime = function(inText) {
						var elemValue = document.getElementById(inText).value;
			console.log("validate Time: " + inText +"  "+ elemValue);
			// STRING FORMAT hh:mm am|pm
			var regex = /^(\d|[1][0-2]):([0-5]\d)\s?(?:AM|PM)$/i;
		    return regex.test(elemValue);
		    
		} // end of _time()
		
		    
        return {      
            attachForm: attachForm,
            initform: initform,
            getFormValues: getFormValues,
            setFormValues: setFormValues,
            validateForm: validateForm,
            show: show,
            hide: hide   
        }
     
  }
})();

//////////// end  module                   ////////////////////////////////////////////

            
           


