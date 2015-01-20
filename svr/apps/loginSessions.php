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

*/

// *****************************************************************




/* ****    login/session queries     *************************************************** */

$createTableSql =  'CREATE TABLE tstbcmdna ('
                    .'id INTEGER(10) UNSIGNED AUTO_INCREMENT, '
                    .'name VARCHAR(100), '
                    .'emailadr VARCHAR(100), '
                    .'appointment DATETIME DEFAULT NULL, '
                    .'PRIMARY KEY (id))';


$_sqlQuery['createLoginTable']['description'] = "create a new appointment table";
$_sqlQuery['createLoginTable']['querystring'] = $createTableSql; 



	
$_sqlQuery['validateCredentials']['description'] = "check if user has valid account";
$_sqlQuery['validateCredentials']['querystring'] =  "SELECT accid, uname FROM tstaccnt WHERE uname=:uname AND pwd=:upwd";






/**********************************************************
function    : clientRequest
description : xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx





***********************************************************/
function clientRequest($cliReq, $dbHdlr)
{
	
	switch ($cliReq->msgtype)
	{
		case "LOGIN":
		        dnaAppLog("LOGIN", " got: " . $cliReq->params->uname . "  " . $cliReq->params->upwd);
		  //      addAppointment($dbHdlr, $cliReq->name, $cliReq->email, $cliReq->datetime);
		        $loginAttemptInfo = validCredentials($dbHdlr, $cliReq->params);
		        $status = "ok";
		        $nextLink = "main.html";
		        $response = "";
		        if($loginAttemptInfo['id'] == 0){
					$nextLink = "";
					$status = "err";
					$response = "Incorrect information provided";
				}
				$msgResp['response'] = array('status' => $status, 'goTo' => $nextLink, 'respmsg' => $response, 'userinfo' => $loginAttemptInfo);
				$msgResp['event'] = $cliReq->event;
//				$msgResp['status'] = $status ;
//				$msgResp['msg'] = $response ;				
				break;
/*

		case "ADD":
		        dnaAppLog("ADD DEAN", " this far..");
		        dnaAppLog("ADD", " got: " . $cliReq->params->name);
		  //      addAppointment($dbHdlr, $cliReq->name, $cliReq->email, $cliReq->datetime);
		        validCredentials($dbHdlr, $cliReq->params);
				$msgResp['response'] = $dbHdlr->rowCount();
				$msgResp['event'] = $cliReq->event;
				$msgResp['status'] = "ok";				
				break;

*/


/*
		case "CRTBL":
				dnaAppLog("CRTBL", " yeah...GOT HERE ******");
				createAppointmentsTable($dbHdlr);
				$msgResp['response'] = "created table";
				$msgResp['status'] = "ok";				
			break;
*/


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
 
function createLoginSessionTable($dbIF)
{
	global $_sqlQuery;

    $crAppSQL = $_sqlQuery['createLoginTable']['querystring'];
    dnaAppLog("CRTBL", $crAppSQL);
    $dbIF->query($crAppSQL);
    $dbIF->execute();  
    
}  // end of createLoginSessionTable()


////////////////////////////
/**********************************************************
function    : getlanguageSpecificText
description : cvbncgncvb ghj fgj fgjfgjfgj fgjfgj 
***********************************************************/
// orig  function addAppointment($dbIF, $name, $emailAdr, $datetime)
function validCredentials($dbIF, $qParams)
{
	global $_sqlQuery;

    dnaAppLog("VALIDATEUSER", ':name '. $qParams->uname . ' :emailAdr '. $qParams->upwd);
    $validUserSQL = $_sqlQuery['validateCredentials']['querystring'];
    $dbIF->query($validUserSQL);
    $dbIF->bind(':uname', $qParams->uname);
	$dbIF->bind(':upwd', $qParams->upwd);
	
	
// dna nov 29, works    $dbIF->execute();
    $row = $dbIF->single();
    dnaAppLog("VALIDATEUSER MID", 'id '. $row['accid']. ' name '. $row['uname']);
    
    $uid = 0;
    $uname = "";
    if($row['accid'] > 0){
         $uid = $row['accid'];
         $uname = $row['uname'];
    }
    
    dnaAppLog("VALIDATEUSER exit", 'id '. $uid. ' name '. $uname);
   
 //   return $rows;
    return array('id' => $uid , 'name' => $uname ); 
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

    dnaAppLog("UPDATEAPPNTMNT", ':name '. $qParams->name . ' :emailAdr '. $qParams->email .' :datetime '. $qParams->datetime);
    $addAppSQL = $_sqlQuery['updateAppointment']['querystring'];
    $dbIF->query($addAppSQL);
    $dbIF->bind(':name', $qParams->name, PDO::PARAM_STR);
	$dbIF->bind(':emailAdr', $qParams->email, PDO::PARAM_STR);
	$dbIF->bind(':datetime', $qParams->datetime);
	$dbIF->bind(':id', $qParams->id, PDO::PARAM_INT);
	
    $dbIF->execute();
    
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




?>




  

