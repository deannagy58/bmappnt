<?php
/*
  oct 18, 2014: doesnt seem to handle utf 8/international charset





 
 */

// from http://culttt.com/2012/10/01/roll-your-own-pdo-php-class/
// Include database class

require_once "./dbQuery.php";
require_once "./dbClass.php";
require_once "./dbFuncs.php";


// Define configuration
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "rfvijn");
define("DB_NAME", "testdb");

//phpinfo();

/* encoding issue
http://stackoverflow.com/questions/1650369/php-utf-8-to-windows-command-line-encoding
* 
* what is chcp 65001 ???
*/
// echo " start... " . "Iñtërnâtiônàlizætiøn" . "\n";
// echo "字是甚麼意思". "\n";

  $date = '2012-03-06 17:33:07';
  $aDate = $date;
  echo "date: " . $aDate."\n";

// Instantiate database.
$database = new Database();


echo "start...";
/*

$database->query('SELECT myname, mynickname, mypasswd FROM talkeeu WHERE ttuserid = :idOfInterest');

$database->bind(':idOfInterest', 2);
$row = $database->single();

echo "<pre>";
print_r($row);
echo "</pre>";

*/

/*
$database->query('INSERT INTO tstappnt (name, emailadr, appointment) VALUES (:name, :emailAdr, :datetime)');

$database->bind(':name', 'rico gash');
$database->bind(':emailAdr', 'qwerty@jones.ca');
$database->bind(':datetime', '2014-11-02 17:00:00');


$database->execute();

echo "last inserted id: " .$database->lastInsertId()."\n";
*/
//////////////////////////////////////////////////////
/*
$database->query('SELECT emailadr, appointment FROM tstappnt WHERE name = :POI');

$database->bind(':POI', "rico gash");
$row = $database->single();

echo "<pre>";
print_r($row);
echo "</pre>";
* */
///////////////////////////////////////////////////

addAppointment($database, "berry wom", "wombat@qwerty.com", "2014-10-25 17:00");
echo "addAppointment num recs: " . $database->rowCount()."\n";


echo "\n\n";

$rows = getAppointmentsForDate($database, '2014-11-04');
echo "getAppointmentsForDate num recs: " . $database->rowCount()."\n";
$jsonResults = json_encode($rows);
echo "getAppointmentsForDate json: " .$jsonResults."\n";

echo "\n\n";

$rows = getAllAppointments($database);
echo "getAllAppointments num recs: " . $database->rowCount()."\n";
$jsonResults = json_encode($rows);
echo "getAllAppointments json: " .$jsonResults."\n";
echo "\n\n";

//echo "debug: " . $database->debugDumpParams() . "\n";


//echo "<pre>";
//print_r($rows);
//echo "</pre>";

//foreach ($rows as $re) {
//        echo "entry: ". $re['name']. " app: " . $re['appointment'] ."\n";
//}

//  easy
//$jsonResults = json_encode($rows);
//echo "json: " .$jsonResults."\n";
//   var_dump(json_decode($jsonResults));
//   var_dump(json_decode($jsonResults, true));

?>
