<?php
require_once( 'session.php' );
require_once( 'common_top.php' );

$challenge_id = (isset($_GET['challenge_id'])) ? htmlspecialchars( $_GET['challenge_id'] ) : -1;
$user_id = $user->uid;
$trusted = 'OK';
require_once( 'challenge_dl.php' );


?>
<p class='h4'>Challenge Detail</p>
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
<br>You opened this page with arguments (<?php echo $challenge_id . '<--    -->' . $user_id; ?>) so it will show you the challenge for that date.
<?php
}
?>

<?php
require_once( 'common_bottom.php' );
?>