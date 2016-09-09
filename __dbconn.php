<?php
//$servername = "eap-app-mysql";
//$username = "userx4C";
//$password = "1huOBjL5";
//$servername = "mysql";
//$username = "weightchallenge";
//$password = "weightchallenge";

define( 'DB_NAME', getenv( 'DB_NAME' ) );
define( 'DB_USER', getenv( 'DB_USER' ) );
define( 'DB_PASS', getenv( 'DB_PASS' ) );

$servername = DB_NAME;
$username = DB_USER;
$password = DB_PASS;


// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
