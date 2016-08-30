<?php
class Database{
  private $username = 'userx4C';
  private $password = '1huOBjL5';
  private $host = 'localhost';
//  private $host = '127.0.0.1';
  private $db_name = 'eap-app-mysql';
  private $port = '3306';
  public $conn;

  public function dbConnection(){
    $this->conn = null;
    try{
      $this->conn = new PDO( "mysql:host={$this->host};port={$this->port};dbname={$this->db_name}", $this->username, $this->password );
      $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }
    catch( Exception $exception ){
      echo "Connection error: " . $exception->getMessage();
      die();
    }

    return $this->conn;
  }
}
?>