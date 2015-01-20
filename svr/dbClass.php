<?php

/*
  oct 18, 2014: doesnt seem to handle utf 8/international charset





 
 */
class Database{
 
	private $host = DB_HOST;
	private $user = DB_USER;
	private $pass = DB_PASS;
	private $dbname = DB_NAME;

    private $dbh;
    private $error;
    
    private $stmt;
    
    
	public function __construct(){
	  // Set DSN
      $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . "; charset=utf8;";
      // Set options
      $options = array(
            PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
      );
      // Create a new PDO instanace
      try{
          $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
      }
      // Catch any errors
      catch(PDOException $e){
          $this->error = $e->getMessage();
      }
	}

	public function query($query){
	    $this->stmt = $this->dbh->prepare($query);
	    if (!$this->stmt) {
		    print_r($dbh->errorInfo());
		}
	}
 
	 public function bind($param, $value, $type = null){
		if (is_null($type)) {
		  switch (true) {
		    case is_int($value):
		      $type = PDO::PARAM_INT;
		      break;
		    case is_bool($value):
		      $type = PDO::PARAM_BOOL;
		      break;
		    case is_null($value):
		      $type = PDO::PARAM_NULL;
		      break;
		    default:
		      $type = PDO::PARAM_STR;
		  }
		}	 
		$this->stmt->bindValue($param, $value, $type);

	} // end of Database::bind()
 
	 public function execute(){
	    return $this->stmt->execute();
	}// end of Database::bind()

	public function resultset(){
	    $this->execute();
	    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	} // end of Database::resultset()
	
	public function single(){
	    $this->execute();
	    return $this->stmt->fetch(PDO::FETCH_ASSOC);
	} // end of Database::single()
	
	public function rowCount(){
	    return $this->stmt->rowCount();
	} // end of Database::rowCount()
	
	public function lastInsertId(){
	    return $this->dbh->lastInsertId();
	} // end of Database::lastInsertId()


	public function beginTransaction(){
	    return $this->dbh->beginTransaction();
	} // end of Database::beginTransaction()

	public function endTransaction(){
	    return $this->dbh->commit();
	} // end of Database::endTransaction()

	public function cancelTransaction(){
	    return $this->dbh->rollBack();
	} // end of Database::cancelTransaction()
	
	public function debugDumpParams(){
       return $this->stmt->debugDumpParams();
    } // end of Database::debugDumpParams()		
    
    	
} // end of Database class


?>
