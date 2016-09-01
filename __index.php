<?php
define( 'DB_NAME', getenv( 'DB_NAME' ) );
define( 'DB_USER', getenv( 'DB_USER' ) );
define( 'DB_PASS', getenv( 'DB_PASS' ) );

echo '<br>Hello World Y';

echo '<br>DB_NAME: ' . DB_NAME;
echo '<br>DB_USER: ' . DB_USER;
//echo '<br>DB_PASS: ' . DB_PASS;
echo '<br>DB_PASS: ' . 'No, not going to show you the pw';

phpinfo();

?>
