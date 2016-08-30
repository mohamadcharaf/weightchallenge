<?php
$servername = "eap-app-mysql";
$username = "userx4C";
$password = "1huOBjL5";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";
?>