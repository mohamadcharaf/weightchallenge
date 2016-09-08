<?php
require_once( 'session.php' );
require_once( 'common_top.php' );

$challenge_id = (isset($_GET['challenge_id'])) ? htmlspecialchars( $_GET['challenge_id'] ) : -1;


// Before using this variable make sure that this user is allowed to see it!

?>
<p class="h4">Challenge Detail</p>
<hr>
<br>This will show you the progress on your challenge
<br>It can also show details on old challenges.

<?php
if( $challenge_id == -1 ){
?>
<br>You opened this page with no arguments so it will show you the present challenge. Or give a message that you are not participating in any challenges.
<?php
}
else{
?>
<br>You opened this page with argumetns so it will show you the challenge for that date.
<?php
}
?>

<?php
require_once( 'common_bottom.php' );
?>