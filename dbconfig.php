<?php
define( 'DB_USER', getenv( 'OPENSHIFT_MYSQL_DB_USERNAME' ) );
define( 'DB_PASS', getenv( 'OPENSHIFT_MYSQL_DB_PASSWORD' ) );
define( 'DB_HOST', getenv( 'OPENSHIFT_MYSQL_DB_HOST' ) );
define( 'DB_NAME', getenv( 'OPENSHIFT_GEAR_NAME' ) );
define( 'DB_PORT', getenv( 'OPENSHIFT_MYSQL_DB_PORT' ) );

class Database{
  private $username = DB_USER;
  private $password = DB_PASS;
  private $host = DB_HOST;
  private $db_name = DB_NAME;
  private $port = DB_PORT;
  public $conn;

  public function dbConnection(){
    $this->conn = null;
    try{
      $this->conn = new PDO( "mysql:host={$this->host};port={$this->port};dbname={$this->db_name}", $this->username, $this->password );
//      $this->conn = new PDO( "mysql:port={$this->port};dbname={$this->db_name}", $this->username, $this->password );
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