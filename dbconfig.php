<?php
class Database{
  private $servername = "mysql";
  private $username = 'weightchallenge';
  private $password = 'weightchallenge';
/*
  private $host = 'weight-challenge-mysql-weight-challenge.0ec9.hackathon.openshiftapps.com';
  private $db_name = 'eap-app-mysql';
  private $port = '3306';
*/

/*
  private $username = 'openshifty';
  private $password = 'oneseventwelve22';
  private $host = 'imadethis.freitag.theinscrutable.us';
  private $db_name = 'openshifty';
  private $port = getenv( 'DB_PORT' );
*/
  public $conn;

  public function dbConnection(){
    $this->conn = null;
    try{
//      $this->conn = new PDO( "mysql:host={$this->host};port={$this->port};dbname={$this->db_name}", $this->username, $this->password );
      $this->conn = new PDO( "mysql:dbname={$this->servername}", $this->username, $this->password );
      $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }
    catch( Exception $exception ){
      echo 'Connection error: ' . $exception->getMessage();
      die();
    }

    return $this->conn;
  }
}
?>
