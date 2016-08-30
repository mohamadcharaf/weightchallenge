<?php
class Database{
  private $username = 'userx4C';
  private $password = '1huOBjL5';
  private $host = 'localhost';
  private $db_name = 'eap-app-mysql';
  private $port = '3306';
  public $conn;

  public function dbConnection(){
    $this->conn = null;
    try{
//      $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
//      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $this->conn = new PDO( "mysql:host={$this->host};port={$this->port};dbname={$this->db_name}", $this->username, $this->password );
      $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }
    catch(P DOException $exception ){
      echo "Connection error: " . $exception->getMessage();
    }

    return $this->conn;
  }
}
?>