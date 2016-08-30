<?php

/*
$servername = "eap-app-mysql";
$username = "userx4C";
$password = "1huOBjL5";

// Create connection
$conn = new mysqli($servername, $username, $password);
*/

require_once( 'dbconfig.php' );
$db = new Database();
$conn = $db->dbConnection();

// Check connection
if( $conn->connect_error ){
    die( 'Connection failed: ' . $conn->connect_error );
}
echo '<br>Connected successfully';



/*
echo '<br>Trying to create table';
$sql = "CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(15) NOT NULL,
  `user_email` varchar(40) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `joining_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
$stmt = conn->prepare( $sql );
$stmt->execute();
echo '<br>Table created';
*/

?>