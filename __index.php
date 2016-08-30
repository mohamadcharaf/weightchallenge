<?php
define( 'DB_HOST', getenv( 'OPENSHIFT_MYSQL_DB_HOST' ) );
define( 'DB_NAME', getenv( 'OPENSHIFT_GEAR_NAME' ) );
define( 'DB_PORT', getenv( 'OPENSHIFT_MYSQL_DB_PORT' ) );

echo '<br>Hello World';
echo '<br>DB_HOST' . DB_HOST;
echo '<br>DB_NAME' . DB_NAME;
echo '<br>DB_PORT' . DB_PORT;
phpinfo();
?>