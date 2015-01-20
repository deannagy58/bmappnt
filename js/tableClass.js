// table class
//
var tableClass = function () {};

tableClass.prototype.testDummy = function(aStr){
	    console.log("table clas: test dummy: "+ aStr);

	};
	
			
tableClass.prototype.convertTime24to12 = function(time24){
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


tableClass.prototype.appointmentsTable = function(tableResult, specialCells)
		{
			var count =0;
			var tableData = tableResult.info;
			var tableHeader = tableResult.tblhdr;
			var cellText = 0;
			var cell= 0;
			var dateTimeSplit;
			
            console.log("updateTableView:" + tableData.length);
            var totalRows = tableData.length;
            var cellsInRow = Object.keys(tableData[0]).length;
            removeElement("appList");
            
      
 
            // get the reference for the body
            var div1 = document.getElementById('appTbl');
 
            // creates a <table> element
            var tbl = document.createElement("table");
            tbl.setAttribute('id',"appList");
            tbl.setAttribute('class',"appcl");

            var hRow = document.createElement("tr");
            for (var k in tableHeader) {
				cell = document.createElement("th");                           
				cellText = document.createTextNode(tableHeader[k]);
                cell.appendChild(cellText);
                hRow.appendChild(cell);
			}
			
			// pad out table header if there are any special type columns
			if (specialCells !== undefined){
				_.forEach(specialCells , function(specialCellObj) {
					cell = document.createElement("th");                           
					cellText = document.createTextNode("");
	                cell.appendChild(cellText);
	                hRow.appendChild(cell);  
				});
			}
			tbl.appendChild(hRow);

            // creating rows
            for (var r = 0; r < totalRows; r++) {
                 var row = document.createElement("tr");
	               var rowName = tableData[r].name;
	               var appDate = tableData[r].appointment;
	               var aRow = tableData[r];
        
                  for (var k in tableData[r]) {
	                  if (tableHeader.hasOwnProperty(k)) {
                          cell = document.createElement("td");
						  if(k === "appointment"){
							 dateTimeSplit = tableData[r][k].split(' ');
		                     cellText = document.createTextNode(dateTimeSplit[0] + " "+ timeUtils.convertTime24to12(dateTimeSplit[1]) );
						  }else{
                              cellText = document.createTextNode(tableData[r][k]);
					      }
                          cell.appendChild(cellText);
                          row.appendChild(cell);
                       }
                  }
                  
                  if (specialCells !== undefined){
					  console.log("size: " + _.size(specialCells));
					  var specialTblFunc = this.specialIDCell;
					  _.forEach(specialCells , function(specialCellObj) {
						  console.log("special: " + specialCellObj.style);
						  row.appendChild(specialTblFunc.call(undefined,specialCellObj, tableData[r]));  
					  });
                  }
                  
	              tbl.appendChild(row); // add the row to the end of the table body
            }
    
            return tbl;
			
		}; // end of function updateTableView()
		
		tableClass.prototype.specialIDCell = function(specialCellObj, modelEntry){
                  // add special characters
                  var cellElem = document.createElement("td");

				  var cellText = document.createElement("span");
				  cellText.id = modelEntry.id;
				  cellText.style = specialCellObj.style; 
				  cellText.innerHTML = specialCellObj.icon;
				  cellText.addEventListener('click', specialCellObj.callback, false);
				  cellText.myParam = modelEntry;
				  cellText.setAttribute('onmouseover', 'style="cursor: pointer;"');                 
                  cellElem.appendChild(cellText);
                  
                  return cellElem;
	};
	
