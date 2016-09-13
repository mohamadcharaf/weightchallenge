<?php
require_once( 'user.php' );
$user = new USER();

function isToday( $time ){
    return( strtotime( $time ) === strtotime( 'today' ) );
}

function isPast( $time ){
    return( strtotime( $time ) < time() );
}

function isFuture( $time ){
    return( strtotime( $time ) > time() );
}
?>

<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <title>Hello World</title>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <script type='text/javascript' src='jquery-3.1.0.min.js'></script>

  <script type='text/javascript' src='jquery.dataTables.min.js'></script>
  <link rel='stylesheet' type='text/css' href='jquery.dataTables.min.css' >
  <script type='text/javascript' src='jquery.webticker.min.js'></script>

<!--  <link rel='stylesheet' type='text/css' media='screen' href='bootstrap/css/bootstrap.min.css'> -->
<!--  <link rel='stylesheet' type='text/css' media='screen' href='bootstrap/css/bootstrap-theme.min.css'> -->

  <link rel='stylesheet' type='text/css' media='screen' href='style.css'>
  <link rel='stylesheet' type='text/css' media='screen' href='bootstrap/css/bootstrap.min.css' />
  <link rel='stylesheet' type='text/css' media='screen' href='bootstrap/css/datepicker3.css' />
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
        <li><a href='history.php'><span class='glyphicon glyphicon-calendar'></span> Challenge History</a></li>
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
?>
<div id='notification_area'>
  <ul id='notification_ticker'>
    <li data-update='item1'>Common content for when you ARE logged in</p></li>
    <li data-update='item2'>You've been invited to participate in a challenge</li>
    <li data-update='item3'>Your weight check in is N days overdue!</li>
    <li data-update='item4'>These are static demo messages.</li>
  </ul>
</div>

<script type='text/javascript'>
$( document ).ready( function(){
debugger;
  jQuery( '#notification_ticker' ).webTicker()
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