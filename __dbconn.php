<?php
define( 'DB_NAME', getenv( 'DB_NAME' ) );
define( 'DB_USER', getenv( 'DB_USER' ) );
define( 'DB_PASS', getenv( 'DB_PASSWORD' ) );
define( 'DB_HOST', getenv( 'DB_HOST' ) );
define( 'DB_PORT', getenv( 'DB_PORT' ) );



// Try connection with mysqli

//$servername = "eap-app-mysql";
//$username = "userx4C";
//$password = "1huOBjL5";
//$servername = "mysql";
//$username = "weightchallenge";
//$password = "weightchallenge";


$servername = DB_HOST;
$db = DB_NAME;
$username = DB_USER;
$password = DB_PASS;


// Create connection
$conn = new mysqli( $db, $username, $password );

// Check connection
if( $conn->connect_error ){
  die( "MySQLi Connection failed: " . $conn->connect_error );
}
echo "MySQLi Connected successfully.";

/**
// Try connection with PDO
class Database{
  private $servername = DB_NAME;
  private $username = DB_USER;
  private $password = DB_PASS;

  public $conn;

  public function dbConnection(){
    $this->conn = null;
    try{
      $this->conn = new PDO( "mysql:host=localhost;dbname={$this->dbname}", $this->username, $this->password );

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
