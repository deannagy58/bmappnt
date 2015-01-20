<?php

// call session_start() to create a session. 
//session_start();  

require_once "./dbClass.php";



// Define configuration
define("DB_HOST", "****");
define("DB_USER", "****");
define("DB_PASS", "****");
define("DB_NAME", "****");

/**
 * dnaAppLog: simple logging to a local text file.
 *
 * @param array $array Multidimensional array to extract keys from
 * @return array
 */
function dnaAppLog($logLevel, $comment)
{
    $logTemplate = "%s [%s] - %s\n"; 
    $file = 'tstAppLog.txt';

    $date = new DateTime();

    $logStr = sprintf($logTemplate,  $date->format('Y-m-d H:i:s'), $logLevel, $comment);

    // Write the contents to the file, 
    // using the FILE_APPEND flag to append the content to the end of the file
    // and the LOCK_EX flag to prevent anyone else writing to the file at the same time
    file_put_contents($file, $logStr, FILE_APPEND | LOCK_EX);
	
} // end of function dnaAppLog()	

	
// Instantiate database.
$database = new Database();



//////////phpinfo(INFO_VARIABLES);
// phpinfo();
// dna apr 16   $json = new Services_JSON();

$msgResp = array('status'=> 'err', 
               'response' => 'Unable to process request',
               'action'=> 'standard',
               'stuff'=> null);
               
if ( isset($_REQUEST["msgReq"])  ){
				   
	$p1Val = $_REQUEST["msgReq"];	
	dnaAppLog("TESTING", "hey: " . $p1Val);		   
}
	
// addAppointment($database, "the great berry wom", "tgb.wombat@qwerty.com", "2014-10-25 17:00");
	
				   

//	$dbConn = new Database();
//	$dbConn->connect();

if ( isset($_REQUEST['msgReq'])  ){

	$clientReq = json_decode($_REQUEST['msgReq']);

	
	switch ($clientReq->resource){
			
		case "APPNT":
		    require_once "apps/appointments.php";
	//	    dnaAppLog("TESTING", $clientReq->resource . " " . $clientReq->aparam1 ."  ". $clientReq->aparam2);
		    $retResp = clientRequest($clientReq, $database);
			break;

		case "LOGINSESSION":
		    require_once "apps/loginSessions.php";
			dnaAppLog("LOGINSESSION", " got req " );
		    $retResp = clientRequest($clientReq, $database);
			break;

		default:
			$msgResp['response'] =  'action not recognized' ;
			$msgResp['action'] =  null ;
			break;
	}  //end of switch
}else{
	$msgResp['response'] =  'request has no action' ;
	$msgResp['action'] =  null ;
    
}

/*
 * sample data
[{"test_idx":"26","test_userid":"1101","test_name":"chrome ","test_description":"from chrome, from home","test_date_time":"2009-07-02 09:21:25","test_count":null},{"test_idx":"25","test_userid":"1101","test_name":"again, ","test_description":"ono here we go again","test_date_time":"2009-07-02 09:20:28","test_count":null},{"test_idx":"23","test_userid":"1101","test_name":"karumba","test_description":"this what i have 2 say","test_date_time":"2009-06-30 23:09:30","test_count":null},{"test_idx":"31","test_userid":"1101","test_name":"aspire","test_description":"from ben jammin","test_date_time":"2009-09-11 21:28:08","test_count":null},{"test_idx":"27","test_userid":"1101","test_name":"x operaaa","test_description":"MODDEDfrom work in opera","test_date_time":"2009-07-02 18:18:31","test_count":null},{"test_idx":"28","test_userid":"1101","test_name":"final day","test_description":"from opera","test_date_time":"2009-07-02 23:32:08","test_count":null},{"test_idx":"14","test_userid":"1101","test_name":"groucho","test_description":"coool guy","test_date_time":"2009-06-16 21:00:26","test_count":null},{"test_idx":"15","test_userid":"1101","test_name":"timmys","test_description":"soon tim man, soon","test_date_time":"2009-07-02 11:01:15","test_count":null},{"test_idx":"16","test_userid":"1101","test_name":"mac name123","test_description":"nother rec moddedits from a mac, crfingers, i t","test_date_time":"2009-07-02 23:37:25","test_count":null},{"test_idx":"20","test_userid":"1101","test_name":"ben jammin","test_description":"from a home mac","test_date_time":"2009-06-23 07:44:46","test_count":null},{"test_idx":"21","test_userid":"1101","test_name":"modded","test_description":"rec 21 modded","test_date_time":"2009-07-02 23:15:07","test_count":null},{"test_idx":"22","test_userid":"1101","test_name":"b jammin","test_description":"still workin","test_date_time":"2009-06-30 19:02:54","test_count":null}]





*/

$output =  json_encode($retResp);

print($output);// echo $output;

  

?>

