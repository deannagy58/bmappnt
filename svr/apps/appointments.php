<?php

//  **************************************************************
//
//  this was from frmMain.php
/*
http://code.tutsplus.com/tutorials/why-you-should-be-using-phps-pdo-for-database-access--net-12059

CREATE TABLE tstdean (id INTEGER(10) UNSIGNED AUTO_INCREMENT, name VARCHAR(100), emailadr VARCHAR(100), appointment DATETIME DEFAULT NULL, PRIMARY KEY (id));



$text = 'Hello ' . $vars->name . ','
      . "\r\n\r\n"
      . 'The second line starts two lines below.'
      . "\r\n\r\n"
      . 'I also don\'t want any spaces before the new line,'
      . ' so it\'s butted up against the left side of the screen.';

return $text;

Regarding the line breaks, with email you should always use \r\n. PHP_EOL is for files 
* that are meant to be used in the same operating system that php is running on.




// list tomorrows appointments
SELECT tstappnt.name, tstappnt.emailadr, tstappnt.appointment AS meetwhen, tstaccnt.uname AS therapist FROM tstappnt 
INNER JOIN tstaccnt ON tstappnt.psychoid = tstaccnt.accid WHERE tstappnt.psychoid=3 AND DATE(tstappnt.appointment) = DATE(DATE_ADD(CURDATE(),INTERVAL 1 DAY));


// SELECT certain DAY FOR certain therapist
SELECT * FROM tstappnt 
WHERE psychoid=3 AND appointment BETWEEN '2014-12-14 00:00:00' AND '2014-12-14 23:59:59';

// SELECT certain MONTH FOR certain therapist
SELECT * FROM tstappnt 
WHERE psychoid=3 AND appointment BETWEEN '2014-12-01 00:00:00' AND '2014-12-31 23:59:59';



*/



// *****************************************************************




/* ****    apointment queries     *************************************************** */

$createTableSql =  'CREATE TABLE tstbcmdna ('
                    .'id INTEGER(10) UNSIGNED AUTO_INCREMENT, '
                    .'name VARCHAR(100), '
                    .'emailadr VARCHAR(100), '
                    .'appointment DATETIME DEFAULT NULL, '
                    .'PRIMARY KEY (id))';


$_sqlQuery['createAppointmentTable']['description'] = "create a new appointment table";
$_sqlQuery['createAppointmentTable']['querystring'] = $createTableSql; 



	
$_sqlQuery['addAppointment']['description'] = "add a new entry appointment";
$_sqlQuery['addAppointment']['querystring'] =  "INSERT INTO tstappnt (name, emailadr, appointment, psychoid) VALUES (:name, :emailAdr, :datetime, :psyid)";

$_sqlQuery['updateAppointment']['description'] = "update an existing appointment";
$_sqlQuery['updateAppointment']['querystring'] =  "UPDATE tstappnt SET name=:name, emailadr=:emailAdr, appointment=:datetime WHERE id=:id";

$_sqlQuery['getDayAppointments']['description'] = "get the appointments for specific date";
$_sqlQuery['getDayAppointments']['querystring'] = "SELECT name, appointment FROM tstappnt WHERE appointment LIKE :DOI";

$_sqlQuery['getAllAppointments']['description'] = "get all appointments ";
$_sqlQuery['getAllAppointments']['querystring'] = "SELECT name, appointment, emailadr, id FROM tstappnt where psychoid=:psyid";

// *********************************************************************
$_sqlQuery['getUpComingAppointments']['description'] = "get all up coming appointments for a particular user";
$_sqlQuery['getUpComingAppointments']['querystring'] = "SELECT name, appointment, emailadr, id FROM tstappnt where appointment > now() and psychoid=:psyid order by appointment asc";

$_sqlQuery['overlapAppointments']['description'] = "find if any appointments overlap with appointment of interest (aoi)";
$_sqlQuery['overlapAppointments']['querystring'] = "SELECT appointment FROM tstappnt where psychoid=:psyid and (appointment between :aoi and (:aoi + interval 1 hour) or (appointment + interval 1 hour) between :aoi and (:aoi + interval 1 hour))";

// **************************************************************************************
$_sqlQuery['deleteAppointment']['description'] = "delete an appointment ";
$_sqlQuery['deleteAppointment']['querystring'] = "DELETE FROM tstappnt WHERE id =  :delID";





$sql = "DELETE FROM movies WHERE filmID =  :filmID";




/**********************************************************
function    : clientRequest
description : xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx





***********************************************************/
function clientRequest($cliReq, $dbHdlr)
{
	
	switch ($cliReq->msgtype)
	{
		case "LIST":
		        dnaAppLog("GETALL", " yeah...GOT HERE ******");
				$rows = getUpComingAppointments($dbHdlr, $cliReq->params);
				$msgResp['response'] = $rows;
				$msgResp['event'] = $cliReq->event;
				$msgResp['status'] = "ok";				
				break;


		case "ADD":
		        dnaAppLog("ADD DEAN", " this far..");
		        dnaAppLog("ADD", " got: " . $cliReq->params->name);
		  //      addAppointment($dbHdlr, $cliReq->name, $cliReq->email, $cliReq->datetime);
		        $actionResp = addAppointment($dbHdlr, $cliReq->params);
				$msgResp['response'] = $actionResp;
				$msgResp['event'] = $cliReq->event;
//				$msgResp['status'] = "ok";				
				break;

		case "EDIT":
		        dnaAppLog("update DEAN", " this far..");
		        dnaAppLog("update", " got: " . $cliReq->params->name);
		  //      addAppointment($dbHdlr, $cliReq->name, $cliReq->email, $cliReq->datetime);
		        $actionResp = updateAppointment($dbHdlr, $cliReq->params);
				$msgResp['response'] = $actionResp;
				$msgResp['event'] = $cliReq->event;
//				$msgResp['status'] = "ok";				
				break;
				
		case "DELETE":
		        dnaAppLog("GETALL", " yeah...GOT HERE ******");
				$rows = deleteAppointment($dbHdlr, $cliReq->appid);
				$msgResp['response'] = $rows;
				$msgResp['event'] = $cliReq->event;
				$msgResp['status'] = "ok";				
				break;



		case "CRTBL":
				dnaAppLog("CRTBL", " yeah...GOT HERE ******");
				createAppointmentsTable($dbHdlr);
				$msgResp['response'] = "created table";
				$msgResp['status'] = "ok";				
			break;

		case "SESSIONTIMEDOUT":

			break;

		default:
		//
		//  NEED SOME KIND OF AN ERROR PAGE ?????
		//

		
			
	}	
	
	return $msgResp;
	
} // end of frm_pageRequestState()



/**********************************************************
function    : getAppointmentsForDate
description : TODO??????
***********************************************************/
 
function createAppointmentsTable($dbIF)
{
	global $_sqlQuery;

    $crAppSQL = $_sqlQuery['createAppointmentTable']['querystring'];
    dnaAppLog("CRTBL", $crAppSQL);
    $dbIF->query($crAppSQL);
    $dbIF->execute();  
    
}  // end of getAllAppointments()


////////////////////////////
/**********************************************************
function    : getlanguageSpecificText
description : cvbncgncvb ghj fgj fgjfgjfgj fgjfgj 
***********************************************************/
// orig  function addAppointment($dbIF, $name, $emailAdr, $datetime)      psychoid
function addAppointment($dbIF, $qParams)
{
	global $_sqlQuery;

    $returnResp = array('status'=> 'ok');

    $getAppSQL = $_sqlQuery['overlapAppointments']['querystring'];
    $dbIF->query($getAppSQL);
    $dbIF->bind(':psyid', $qParams->psychoid, PDO::PARAM_INT);
    $dbIF->bind(':aoi', $qParams->datetime);

    $row = $dbIF->single();
    dnaAppLog("overlapAppointments MID", 'id '. $row['appointment']);

    if($row['appointment'] != ""){
        $returnResp =  array('status'=> 'err', 'appointment' => $row['appointment'], 'reason' => 'Conflict: there is an appointment at ' );
    }else{
	    dnaAppLog("ADDAPPNTMNT", ':name '. $qParams->name . ' :emailAdr '. $qParams->email .' :datetime '. $qParams->datetime);
	    $addAppSQL = $_sqlQuery['addAppointment']['querystring'];
	    $dbIF->query($addAppSQL);
	    $dbIF->bind(':name', $qParams->name);
		$dbIF->bind(':emailAdr', $qParams->email);
		$dbIF->bind(':datetime', $qParams->datetime);
		$dbIF->bind(':psyid', $qParams->psychoid);
		
	    $dbIF->execute();
    }
    
    return $returnResp;
    
}  // end of addAppointment()


////////////////////////////
/**********************************************************
function    : getlanguageSpecificText
description : cvbncgncvb ghj fgj fgjfgjfgj fgjfgj 
***********************************************************/
// orig  function addAppointment($dbIF, $name, $emailAdr, $datetime)
function updateAppointment($dbIF, $qParams)
{
	global $_sqlQuery;

    $returnResp = array('status'=> 'ok');

    $getAppSQL = $_sqlQuery['overlapAppointments']['querystring'];
    $dbIF->query($getAppSQL);
    $dbIF->bind(':psyid', $qParams->psychoid, PDO::PARAM_INT);
    $dbIF->bind(':aoi', $qParams->datetime);

    $row = $dbIF->single();
    dnaAppLog("overlapAppointments MID", 'id '. $row['appointment']);

    if($row['appointment'] != ""){
        $returnResp =  array('status'=> 'err', 'appointment' => $row['appointment'], 'reason' => 'Conflict: there is an appointment at ' );
    }else{
	    dnaAppLog("UPDATEAPPNTMNT", ':name '. $qParams->name . ' :emailAdr '. $qParams->email .' :datetime '. $qParams->datetime);
	    $addAppSQL = $_sqlQuery['updateAppointment']['querystring'];
	    $dbIF->query($addAppSQL);
	    $dbIF->bind(':name', $qParams->name, PDO::PARAM_STR);
		$dbIF->bind(':emailAdr', $qParams->email, PDO::PARAM_STR);
		$dbIF->bind(':datetime', $qParams->datetime);
		$dbIF->bind(':id', $qParams->id, PDO::PARAM_INT);
		
	    $dbIF->execute();
    }
    
    return $returnResp;
    
}  // end of updateAppointment()

//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
/**********************************************************
function    : getAppointmentsForDate
description : TODO??????
***********************************************************/
 
function getAppointmentsForDate($dbIF, $dateInQuestion)
{
	global $_sqlQuery;

    $getAppSQL = $_sqlQuery['getDayAppointments']['querystring'];
    $dbIF->query($getAppSQL);
    $dbIF->bind(':DOI', $dateInQuestion."%");
    $rows = $dbIF->resultset();   
    
    return $rows;
    
}  // end of getAppointmentsForDate()


/**********************************************************
function    : getUpComingAppointments
description : TODO??????
***********************************************************/
 
function getUpComingAppointments($dbIF, $qParams)
{
	global $_sqlQuery;
 
    $getAppSQL = $_sqlQuery['getUpComingAppointments']['querystring'];
    $dbIF->query($getAppSQL);
    $dbIF->bind(':psyid', $qParams->psychoid, PDO::PARAM_INT);
    $rows = $dbIF->resultset();   

    $tableTitles = array('name'=> 'Name', 
               'appointment' => 'Date',
                'emailadr' => 'Email'
                );
                   
    return array('info' => $rows, 'tblhdr' => $tableTitles); 
 
    
}  // end of getUpComingAppointments()



//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
/**********************************************************
function    : deleterecord
description : TODO??????
***********************************************************/
 
function deleteAppointment($dbIF, $appID)
{
	global $_sqlQuery;
	
	$stmt = $_sqlQuery['deleteAppointment']['querystring'];
	$dbIF->query($stmt);
//  what does it do ????	$stmt->bindParam(':delID', $appID, PDO::PARAM_INT); 
    $dbIF->bind(':delID', $appID); 
	$dbIF->execute();

}  // end of deleteAppointment()


?>




  

