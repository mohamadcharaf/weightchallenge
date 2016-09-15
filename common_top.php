<?php
ob_start();

require_once( 'user.php' );
$user = new USER();

$uid = $user->getUID();

//QQQ Move all these global functions into a utility object
function isToday( $time ){
  return( strtotime( $time ) === strtotime( 'today' ) );
}

function isPast( $time ){
  return( strtotime( $time ) < time() );
}

function isFuture( $time ){
  return( strtotime( $time ) > time() );
}

/** Messages
 **
 ** 0 - not unique - expires one week after first posting
 ** 1 - unique - Pending invitations
 ** 2 - unique - not assigned yet

QQQ future messages to add
"You have N challenges expiring in the next week"
"You have N challenges starting in the next week"
"You have not weighed in for N days!" (show regardless of challenge activity status)
"You are not participating in any challenges.  Why not create one and challenge your friends?"

QQQ Create a static Notification object with a couple of funcitons
$mesg->add()        to replace addNotification()
$mesg->maintain()   to delete old msgs for present user
 **/
function delNotification( $uid, $msgType ){
  global $user;
  $msgIdList = array( 1, 2 );
  if( in_array( $msgType, $msgIdList ) ){
    $sql_string = '
    DELETE FROM wc__notifications
     WHERE fk_user_id = :uid
       AND msg_id = :msg_type';
    $stmt = $user->prepQuery( $sql_string );
    $stmt->bindParam( ':uid', $uid );
    $stmt->bindParam( ':msg_type', $msgType );
    $stmt->execute();
  }
}

function addNotification( $uid, $data, $msgType ){
  global $user;
  delNotification( $uid, $msgType );

  $msg_text = null;
  switch( $msgType ){
    case 0:
      $msg_text = $data;
    break;
    case 1:
      $msg_text = "You have {$data} pending invitation(s)";
    break;
    case 2:
      $msg_text = "Some message with this {$data} as extra information.";
    break;
    default:  // Do not recognize this message so skip it.
      return;
  }

// QQQ msg_id is NOT a key and need not be unique.
  $sql_string = '
  INSERT INTO wc__notifications( fk_user_id, msg_id, msg_text  )
  VALUES ( :uid, :msg_type, :msg_text )';
  $stmt = $user->prepQuery( $sql_string );
  $stmt->bindParam( ':uid', $uid );
  $stmt->bindParam( ':msg_type', $msgType );
  $stmt->bindParam( ':msg_text', $msg_text );
  $stmt->execute();

  return;
}
?>

<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <title>Weight Challenge</title>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <script type='text/javascript' charset='utf-8' src='jquery-3.1.0.min.js'></script>
  <link rel='stylesheet' type='text/css' media='screen' href='style.css'> <!-- Styling that came with the login template -->

  <script type='text/javascript' charset='utf-8' src='jquery.dataTables.min.js'></script>
  <link rel='stylesheet' type='text/css' href='jquery.dataTables.min.css' >
  <script type='text/javascript' charset='utf-8' src='jquery.webticker.min.js'></script>
  <link rel='stylesheet' type='text/css' href='webticker.css' >

  <script type='text/javascript' src='jquery-ui.min.js'></script> <!-- jQuery UI datepicker stuff (and much more) -->
  <link rel='stylesheet' type='text/css' media='screen' href='jquery-ui.min.css'>

  <script type='text/javascript' charset='utf-8' src='bootstrap/js/bootstrap.min.js'></script>
  <link rel='stylesheet' type='text/css' media='screen' href='bootstrap/css/bootstrap.min.css' />

  <link rel='stylesheet' type='text/css' media='screen' href='bootstrap/css/bootstrap-table.css' />
  <link rel='stylesheet' type='text/css' media='screen' href='bootstrap/css/styles.css' />
</head>
<body>
<?php
if( $user->is_loggedin() ){
?>
<nav class='navbar navbar-default navbar-fixed-top'>
  <div class='container'>
    <div id='navbar' class='navbar-collapse collapse'>
      <ul class='nav navbar-nav'>
        <li><a href='home.php'><span class='glyphicon glyphicon-home'></span> Home</a></li>
        <li><a href='history.php'><span class='glyphicon glyphicon-calendar'></span> Challenges</a></li>
        <li><a href='challenge.php'><span class='glyphicon glyphicon-zoom-in'></span> Challenge Detail</a></li>
        <li><a href='records.php'><span class='glyphicon glyphicon-calendar'></span> Personal Records</a></li>
        <li><a href='about.php'><span class='glyphicon glyphicon-info-sign'></span> About</a></li>
      </ul>

      <ul class='nav navbar-nav navbar-right'>
        <li class='dropdown'>
          <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>
            <span class='glyphicon glyphicon-user'></span>&nbsp;Welcome <?php echo $user->getName(); ?>&nbsp;<span class='caret'></span>
          </a>
          <ul class='dropdown-menu'>
            <li><a href='profile.php'><span class='glyphicon glyphicon-cog'></span>&nbsp;View Profile</a></li>
            <li><a href='logout.php?logout=true'><span class='glyphicon glyphicon-log-out'></span>&nbsp;Sign Out</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<?php
//QQQ Add code here to check notifications table to see if user has any.
//QQQ Add them to some kind of scrolling banner.  Show one, wait 30 seconds, show next.
//QQQ Leave notifications showing in UI until they are acknowledged
//QQQ In some timer driven ajax script once every 5 minutes rebuild the notificaiton list.


/** Look for and perform status changes.
 ** Status are one of Invited, Accepted, Declined, Participating, Complete, or Disqualified'
 **/

// Check if too many weigh-ins were missed (Participating -> Disqualified)
//QQQ Need a warning that a limit is approaching before you get disqualified
$sql_string = '
UPDATE wc__challenge_participant wcp
   SET wcp.status = "Disqualified"
 WHERE wcp.fk_user_id = :uid
   AND wcp.status = "Participating"
   AND wcp.end_date < now()
   AND 5 < (SELECT COUNT(*)
              FROM wc__user_weigh_in
             WHERE weight = NULL
               AND weigh_date BETWEEN wcp.start_date AND wcp.end_date )';
$stmt = $user->prepQuery( $sql_string );
$stmt->bindParam( ':uid', $uid );
$stmt->execute();
$disqualifiedCount = $stmt->rowCount();
if( $disqualifiedCount > 0 ){
  addNotification( $uid, "Oh no! On {date('Y-m-d')} you were disqualified from $disqualifiedCount challenges due to missed weigh ins.", 0 );
}
//QQQ Notifications get an added_on date.  They go away after a week.  They can be manually dismissed.
//QQQ If you have more than 5 notifications, the scroller will add "You have N notifications" where N is the count of them

// Check if any challenge has ended. ( Participating -> Complete )
$sql_string = '
UPDATE wc__challenge_participant
   SET status = "Complete"
 WHERE fk_user_id = :uid
   AND status = "Participating"
   AND end_date < now()';
$stmt = $user->prepQuery( $sql_string );
$stmt->bindParam( ':uid', $uid );
$stmt->execute();
$completeCount = $stmt->rowCount();
if( $completeCount > 0 ){
  addNotification( $uid, "On _today_ you completed $completeCount challenge(s).  Check your final rank and brag to your buddies!", 0 );
}


// Check for missed challenges - ( Invited/Accepted -> Declined if you did not participate)
$sql_string = '
UPDATE wc__challenge_participant
   SET status = "Declined"
 WHERE fk_user_id = :uid
   AND ( status = "Invited" OR status = "Accepted" )
   AND end_date < now()';
$stmt = $user->prepQuery( $sql_string );
$stmt->bindParam( ':uid', $uid );
$stmt->execute();
$completeCount = $stmt->rowCount();
if( $completeCount > 0 ){
  addNotification( $uid, "On {date('Y-m-d')} you completed $completeCount challenge(s).  Check your final rank and brag to your buddies!", 0 );
}

// Check for challenge starting ( Accepted -> Participating )
$sql_string = '
UPDATE wc__challenge_participant
   SET status = "Participating"
 WHERE fk_user_id = :uid
   AND status = "Accepted"
   AND now() BETWEEN start_date AND end_date';
$stmt = $user->prepQuery( $sql_string );
$stmt->bindParam( ':uid', $uid );
$stmt->execute();
$startingCount = $stmt->rowCount();
if( $startingCount > 0 ){
  addNotification( $uid, "On {date('Y-m-d')} you $startingCount challenge(s) have started", 0 );
}

//QQQ Hmm, what to do if you log in late and it starts and you are immediately overdue more than 5 days?

// Show pending invitations - if any challenge is marked as Invited and now() < start_date, show invitation
$sql_string = '
SELECT COUNT(*)
  FROM wc__challenge_participant
 WHERE fk_user_id = :uid
   AND status = "Invited"';
$stmt = $user->prepQuery( $sql_string );
$stmt->bindParam( ':uid', $uid );
$stmt->execute();
$inviteCount = $stmt->fetchColumn();
if( $inviteCount > 0 ){
  addNotification( $uid, $inviteCount, 1 );
}
else if( $inviteCount = 0 ){
  delNotification( $uid, 1 );
}

// Maintain notifications (clear week old regular messages)
$sql_string = '
DELETE FROM wc__notifications
 WHERE fk_user_id = :uid
   AND msg_id = 0
   AND added_on < NOW() - INTERVAL 1 WEEK';
$stmt = $user->prepQuery( $sql_string );
$stmt->bindParam( ':uid', $uid );
$stmt->execute();

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

<script type='text/javascript'>
$( document ).ready( function(){
  $( '#notification_ticker' ).webTicker({
      speed:         50       // pixels per second
      ,direction:    'left'   // if to move left or right
      ,moving:       true     // weather to start the ticker in a moving or static position
      ,startEmpty:   true     // weather to start with an empty or pre-filled ticker
      ,duplicate:    false    // if there is less items then visible on the ticker you can duplicate the items to make it continuous
      ,rssurl:       false    // only set if you want to get data from rss
      ,rssfrequency: 0        // the frequency of updates in minutes. 0 means do not refresh
      ,updatetype:   'reset'  // how the update would occur options are "reset" or "swap"
  });

  $( '#notification_area' ).unbind( 'click' ).click( function(){
    window.location = 'notifications.php';
  });

});
</script>
<?php
}
else{
// Logged out view
?>

<?php
}
?>

<div class='clearfix'></div>
  <div class='container-fluid' style='margin-top:8px;'>
    <div class='container'>