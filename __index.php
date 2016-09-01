<?php
define( 'DB_NAME', getenv( 'DB_NAME' ) );
define( 'DB_USER', getenv( 'DB_USER' ) );
define( 'DB_PASS', getenv( 'DB_PASS' ) );

echo '<br>Hello World';

echo '<br>DB_HOST: ' . DB_HOST;
echo '<br>DB_USER: ' . DB_USER;
echo '<br>DB_PASS: ' . DB_PASS;

phpinfo();

?>
