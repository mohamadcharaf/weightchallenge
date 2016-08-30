<?php
//define( 'DB_HOST', getenv( 'OPENSHIFT_MYSQL_DB_HOST' ) );
//define( 'DB_PORT', getenv( 'OPENSHIFT_MYSQL_DB_PORT' ) );

define( 'DB_HOST', getenv( 'EAP_APP_MYSQL_SERVICE_HOST' ) );
define( 'DB_PORT', getenv( 'MYSQL_SERVICE_PORT' ) );


define( 'DB_USER', getenv( 'OPENSHIFT_MYSQL_DB_USERNAME' ) );
define( 'DB_PASS', getenv( 'OPENSHIFT_MYSQL_DB_PASSWORD' ) );
define( 'DB_SOCK', getenv( 'OPENSHIFT_MYSQL_DB_SOCKET' ) );
define( 'DB_URL', getenv( 'OPENSHIFT_MYSQL_DB_URL' ) );

echo '<br>Hello World';
echo '<br>DB_HOST: ' . DB_HOST;
echo '<br>DB_PORT: ' . DB_PORT;
echo '<br>DB_USER: ' . DB_USER;
echo '<br>DB_PASS: ' . 'No, not going to show you the pw';
echo '<br>DB_SOCK: ' . DB_SOCK;
echo '<br>DB_URL: ' . DB_URL;
phpinfo();
?>