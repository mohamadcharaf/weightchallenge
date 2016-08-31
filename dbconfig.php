<?php
// Convert from PDO to mysqli because openshift returns either error 504 or 502 with PDO
class Database{
  private $username = 'toy_user';
  private $password = 'sp!tup#';
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