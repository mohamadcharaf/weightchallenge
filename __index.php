<?php
define( 'DB_HOST', getenv( 'DB_HOST' ) );
define( 'DB_PORT', getenv( 'DB_PORT' ) );
define( 'DB_NAME', getenv( 'DB_NAME' ) );
define( 'DB_USER', getenv( 'DB_USER' ) );
define( 'DB_PASS', getenv( 'DB_PASSWORD' ) );

echo '<br>Hello World A';

echo '<br>DB_HOST: ' . DB_HOST;
echo '<br>DB_PORT: ' . DB_PORT;
echo '<br>DB_NAME: ' . DB_NAME;
echo '<br>DB_USER: ' . DB_USER;
//echo '<br>DB_PASS: ' . DB_PASS;
echo '<br>DB_PASS: ' . 'No, not going to show you the pw!';

phpinfo();

?>
