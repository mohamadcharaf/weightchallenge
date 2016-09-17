<?php
require_once( 'session.php' );
require_once( 'common_top.php' );


/** Messages
 **
 ** 0 - not unique - expires one week after first posting
 ** 1 - unique - Pending invitations
 ** 2 - unique - not assigned yet

 **/


// Show notifications
$sql_string = '
SELECT msg_text
  FROM wc__notifications
 WHERE fk_user_id = :uid';
$stmt = $user->prepQuery( $sql_string );
$stmt->bindParam( ':uid', $uid );
$stmt->execute();
$notifications = $stmt->fetchAll( PDO::FETCH_NUM );
?>
<div id='notification_area' class='tickercontainer'>
  <ul id='notification_ticker' class='newsticker'>
<?php
$msgNum = 0;
if( isset( $notifications ) ){
  if( count( $notifications ) > 5 ){
    $msgNum++;
    echo "<li data-update='item{$msgNum}'>You have many notifications!  Click here to manage them.</p></li>";
  }
  foreach( $notifications as $text ){
    $msgNum++;
    echo "<li data-update='item{$msgNum}'>{$text[0]}</p></li>";
  }
}
?>
  </ul>
</div>


<p class='h4'>User Home Page</p>
<hr>
This is where your dashboard will show up.
<?php
require_once( 'common_bottom.php' );
?>