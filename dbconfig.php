<?php
/*
if( getenv( 'OPENSHIFT_MYSQL_DB_HOST' ) ){
  define( 'DB_HOST', getenv( 'OPENSHIFT_MYSQL_DB_HOST' ) );
  define( 'DB_PORT', getenv( 'OPENSHIFT_MYSQL_DB_PORT' ) );
  define( 'DB_USER', getenv( 'OPENSHIFT_MYSQL_DB_USERNAME' ) );
  define( 'DB_PASS', getenv( 'OPENSHIFT_MYSQL_DB_PASSWORD' ) );
  define( 'DB_SOCK', getenv( 'OPENSHIFT_MYSQL_DB_SOCKET' ) );
  define( 'DB_URL', getenv( 'OPENSHIFT_MYSQL_DB_URL' ) );

  define( 'DB_NAME', 'eap-app-mysql' );
}
else{
//  define( 'DB_HOST', 'localhost' );
//  define( 'DB_PORT', '3306' );
*/
  define( 'DB_USER', 'userx4C' );
  define( 'DB_PASS', '1huOBjL5' );

  define( 'DB_NAME', 'eap-app-mysql' );
//  define( 'DB_SOCK', getenv( 'OPENSHIFT_MYSQL_DB_SOCKET' ) );
//  define( 'DB_URL', getenv( 'OPENSHIFT_MYSQL_DB_URL' ) );
}


class Database{
  private $username = DB_USER;
  private $password = DB_PASS;
//  private $host = DB_HOST;
  private $db_name = DB_NAME;
//  private $port = DB_PORT;
  public $conn;

  public function dbConnection(){
    $this->conn = null;
    try{
//      $this->conn = new PDO( "mysql:host={$this->host};port={$this->port};dbname={$this->db_name}", $this->username, $this->password );
      $this->conn = new PDO( "mysql:dbname={$this->db_name}", $this->username, $this->password );
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