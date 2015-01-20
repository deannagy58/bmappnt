// time class
//
var timeClass = function () {};

timeClass.prototype.Converttimeformat = function(time12hr){
		var time = time12hr;
		var hrs = Number(time.match(/^(\d+)/)[1]);
		var mnts = Number(time.match(/:(\d+)/)[1]);
		var format = time.substr(time.indexOf(":")+3, 3);
		if (format.toLowerCase().trim() === "pm" && hrs < 12) 
			hrs = hrs + 12;
			
		if (format.toLowerCase().trim() === "am" && hrs == 12) 
			hrs = hrs - 12;
			
		var hours = hrs.toString();
		var minutes = mnts.toString();
		if (hrs < 10) 
			hours = "0" + hours;
		if (mnts < 10) 
			minutes = "0" + minutes;
			
		return hours + ":" + minutes;
	};
	
			
timeClass.prototype.convertTime24to12 = function(time24){
		var tmpArr = time24.split(':'), time12;
		if(+tmpArr[0] == 12) {
			time12 = tmpArr[0] + ':' + tmpArr[1] + ' pm';
		} else {
			if(+tmpArr[0] == 00) {
				time12 = '12:' + tmpArr[1] + ' am';
			} else {
				if(+tmpArr[0] > 12) {
					time12 = (+tmpArr[0]-12) + ':' + tmpArr[1] + ' pm';
				} else {
					time12 = (+tmpArr[0]) + ':' + tmpArr[1] + ' am';
				}
			}
		}
		return time12;
	};		
