<?php
class Database{
  private $servername = 'mysql';
  private $username = 'weightchallenge';
  private $password = 'weightchallenge';

  public $conn;

  public function dbConnection(){
    $this->conn = null;
    try{
      $this->conn = new PDO( "mysql:server={$this->servername}", $this->username, $this->password );
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
