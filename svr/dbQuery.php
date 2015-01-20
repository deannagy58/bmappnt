<?php

/*

this file contains dfgjkhlk jhlsdfkjghl ksdjhglkjhsdlkjghlksdhgklj h


"CREATE TABLE tstdean (id INTEGER(10) UNSIGNED AUTO_INCREMENT, name VARCHAR(100), emailadr VARCHAR(100), appointment DATETIME DEFAULT NULL, PRIMARY KEY (id))";


*/

/* ****    apointment queries     *************************************************** 
 
*/

$createTableSql =  'CREATE TABLE tstbcmdna ('
                    .'id INTEGER(10) UNSIGNED AUTO_INCREMENT, '
                    .'name VARCHAR(100), '
                    .'emailadr VARCHAR(100), '
                    .'appointment DATETIME DEFAULT NULL, '
                    .'PRIMARY KEY (id))';


$_sqlQuery['createAppointmentTable']['description'] = "create a new appointment table";
$_sqlQuery['createAppointmentTable']['querystring'] = $createTableSql; 



	
$_sqlQuery['addAppointment']['description'] = "add a new entry appointment";
$_sqlQuery['addAppointment']['querystring'] =  "INSERT INTO tstappnt (name, emailadr, appointment) VALUES (:name, :emailAdr, :datetime)";

$_sqlQuery['getDayAppointments']['description'] = "get the appointments for specific date";
$_sqlQuery['getDayAppointments']['querystring'] = "SELECT name, appointment FROM tstappnt WHERE appointment LIKE :DOI";

$_sqlQuery['xxxgetAllAppointments']['description'] = "get all appointments ";
$_sqlQuery['xxxgetAllAppointments']['querystring'] = "SELECT name, appointment, emailadr FROM tstappnt";


	
?>

