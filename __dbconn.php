<?php
define( 'DB_HOST', getenv( 'DB_HOST' ) );
define( 'DB_PORT', getenv( 'DB_PORT' ) );
define( 'DB_NAME', getenv( 'DB_NAME' ) );
define( 'DB_USER', getenv( 'DB_USER' ) );
define( 'DB_PASS', getenv( 'DB_PASSWORD' ) );


/**
// Try connection with mysqli
$servername = DB_HOST;
$dbname = DB_NAME;
$username = DB_USER;
$password = DB_PASS;
**/

//$dbname = "eap-app-mysql";
//$username = "userx4C";
//$password = "1huOBjL5";

/** This data is known good **/
//$dbname = "mysql";
//$username = "weightchallenge";
//$password = "weightchallenge";

$dbname = DB_NAME;
$username = DB_USER;
$password = DB_PASS;


// Create connection
$conn = new mysqli( $dbname, $username, $password );

// Check connection
if( $conn->connect_error ){
  die( "MySQLi Connection failed: " . $conn->connect_error );
}
echo "MySQLi Connected successfully.";

/**
// Try connection with PDO
class Database{
  private $host = DB_HOST;
  private $port = DB_PORT;
  private $db_name = DB_NAME;
  private $username = DB_USER;
  private $password = DB_PASS;

  public $conn;

  public function dbConnection(){
    $this->conn = null;
    try{
      $this->conn = new PDO( "mysql:host={$this->host};port={$this->port};dbname={$this->db_name}", $this->username, $this->password );

      $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
      echo "PDO Connected successfully!";
    }
    catch( Exception $exception ){
      echo 'PDO Connection error: ' . $exception->getMessage();
      die();
    }

    return $this->conn;
  }
}

$db = new Database();
$db->dbConnection();
**/

?>
