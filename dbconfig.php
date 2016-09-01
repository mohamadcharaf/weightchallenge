<?php
class Database{
/*
  private $dbname = 'mysql';
  private $username = 'weightchallenge';
  private $password = 'weightchallenge';
*/
  private $username = 'openshifty';
  private $password = 'oneseventwelve22';
  private $host = 'imadethis.freitag.theinscrutable.us';
  private $db_name = 'openshifty';
  private $port = '3306';

  public $conn;

  public function dbConnection(){
    $this->conn = null;
    try{
      $this->conn = new PDO( "mysql:host={$this->host};port={$this->port};dbname={$this->db_name}", $this->username, $this->password );
//      $this->conn = new PDO( "mysql:host=localhost;dbname={$this->dbname}", $this->username, $this->password );

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
