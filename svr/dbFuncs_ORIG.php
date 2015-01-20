<?php

//  **************************************************************
//
//
/*
http://code.tutsplus.com/tutorials/why-you-should-be-using-phps-pdo-for-database-access--net-12059

CREATE TABLE tstappnt (
id                  INTEGER(10) UNSIGNED AUTO_INCREMENT,
name                VARCHAR(100),
emailadr          VARCHAR(100),
appointment          DATETIME DEFAULT NULL,
PRIMARY KEY (id)
);

*/

// *****************************************************************

/**
 * dnaAppLog: simple logging to a local text file.
 *
 * @param array $array Multidimensional array to extract keys from
 * @return array
 * date time: http://www.pontikis.net/tip/?id=18
 *            http://www.paulund.co.uk/datetime-php
 * 
 */
function TEMPdnaAppLog($logLevel, $comment)
{
    $logTemplate = "%s [%s] - %s\n"; 
    $file = 'dnaAppLog.txt';

 //   $date = new DateTime('now');

    $mydate = DateTime::createFromFormat( "m-d-Y H:i:s", 'now');
    $logStr = sprintf($logTemplate,  $mydate, $logLevel, $comment);

    // Write the contents to the file, 
    // using the FILE_APPEND flag to append the content to the end of the file
    // and the LOCK_EX flag to prevent anyone else writing to the file at the same time
    file_put_contents($file, $logStr, FILE_APPEND | LOCK_EX);
	
} // end of function TEMPdnaAppLog()	


////////////////////////////
/**********************************************************
function    : getlanguageSpecificText
description : cvbncgncvb ghj fgj fgjfgjfgj fgjfgj 
***********************************************************/




////////////////////////////
/**********************************************************
function    : getlanguageSpecificText
description : cvbncgncvb ghj fgj fgjfgjfgj fgjfgj 
***********************************************************/
function addAppointment($dbIF, $name, $emailAdr, $datetime)
{
	global $_sqlQuery;
//	TEMPdnaAppLog("DEBUG", "add an appointment");
    $addAppSQL = $_sqlQuery['addAppointment']['querystring'];
    $dbIF->query($addAppSQL);
    $dbIF->bind(':name', $name);
	$dbIF->bind(':emailAdr', $emailAdr);
	$dbIF->bind(':datetime', $datetime);
	
    $dbIF->execute();
    
}  // end of addAppointment()




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
function    : getAppointmentsForDate
description : TODO??????
***********************************************************/
 
function getAllAppointments($dbIF)
{
	global $_sqlQuery;

    $getAppSQL = $_sqlQuery['getAllAppointments']['querystring'];
    $dbIF->query($getAppSQL);
    $rows = $dbIF->resultset();   
    
    return $rows;
    
}  // end of getAllAppointments()



//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
/**********************************************************
function    : deleterecord
description : TODO??????
***********************************************************/
 
function deleteAppointment($dbConn, $userID, $yyyy, $mm, $dd, $hh)
{


}  // end of deleteAppointment()


//   accounts

////////////////////////////
/**********************************************************
function    : getlanguageSpecificText
description : cvbncgncvb ghj fgj fgjfgjfgj fgjfgj 
***********************************************************/

function addAccount($dbConn, $userID, $upw, $hKey)
{
//	global $supported_languages;
	global $_sqlQuery;
	
	$retVal = -1;
//	$actionResponse = new functionResponse();
	
//	mysql_query("SET AUTOCOMMIT=0");
//	mysql_query("START TRANSACTION");

	if ($dbConn->getDBState())
	{	

		$addSQL = $_sqlQuery['addAccount']['querystring'];
	
		$dbConn->bind($addSQL, array( 'userID' => $userID, 'upw' => $upw, 'hKey' => $hKey));

		$result = $dbConn->execute($addSQL);

		if ($result)
		{
			//	$actionResponse->setState("success");
				$retVal = mysql_insert_id();

		}
		else
		{
			//	$actionResponse->setState("fail");
			//	$actionResponse->setMessage("update failed: ");
		}

	}
	else
	{
		// db state error
	}
/*
	if ("success" == $actionResponse->getState() )
	{
		mysql_query("COMMIT"); 	
	}
	else
	{
		mysql_query("ROLLBACK");	
	}
*/	
	return $retVal;

}  // end of addAppointment()

?>




  

