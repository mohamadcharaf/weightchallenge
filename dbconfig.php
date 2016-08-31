<?php
// Convert from PDO to mysqli because openshift returns either error 504 or 502 with PDO
class Database{
/*
  private $username = 'userx4C';
  private $password = '1huOBjL5';
  private $host = 'weight-challenge-mysql-weight-challenge.0ec9.hackathon.openshiftapps.com';
  private $db_name = 'eap-app-mysql';
  private $port = '3306';
*/
  private $username = 'openshifty';
  private $password = 'oneseventwelve22';
  private $host = 'imadethis.freitag.theinscrutable.us';
  private $db_name = 'maker';
  private $port = '3306';
  public $conn;

  public function dbConnection(){
    $this->conn = null;
    try{
//      $this->conn = new PDO( "mysql:host={$this->host};port={$this->port};dbname={$this->db_name}", $this->username, $this->password );
//      $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
      $this->conn = new mysqli($this->host, $this->username, $this->password);
      if( ! mysqli_select_db( $this->conn, $this->db_name ) ){
        throw new Exception( 'Cannot select DB' );
      }
    }
    catch( Exception $exception ){
      echo 'Connection error: ' . $exception->getMessage();
      die();
    }

    return $this->conn;
  }
}
?>