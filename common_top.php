<?php
require_once( 'user.php' );
$user = new USER();
?>

<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <title>Hello World</title>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
  <script type='text/javascript' src='jquery-3.1.0.min.js'></script>

  <script type='text/javascript' src='jquery.dataTables.min.js'></script>
  <link rel='stylesheet' type='text/css' href='jquery.dataTables.min.css' >

<!--  <link rel='stylesheet' type='text/css' media='screen' href='bootstrap/css/bootstrap.min.css'> -->
<!--  <link rel='stylesheet' type='text/css' media='screen' href='bootstrap/css/bootstrap-theme.min.css'> -->
<!--  <link rel='stylesheet' type='text/css' media='screen' href='style.css'> -->

  <link rel='stylesheet' type='text/css' media='screen' href='bootstrap/css/bootstrap.min.css' />
  <link rel='stylesheet' type='text/css' media='screen' href='bootstrap/css/datepicker3.css' />
  <link rel='stylesheet' type='text/css' media='screen' href='bootstrap/css/bootstrap-table.css' />
  <link rel='stylesheet' type='text/css' media='screen' href='bootstrap/css/styles.css' />
</head>
<body>
<nav class='navbar navbar-default navbar-fixed-top'>
  <div class='container'>
    <div id='navbar' class='navbar-collapse collapse'>
      <ul class='nav navbar-nav'>
<?php
if( $user->is_loggedin() ){
?>
        <li><a href='home.php'><span class='glyphicon glyphicon-home'></span> Home</a></li>
        <li><a href='history.php'><span class='glyphicon glyphicon-calendar'></span> Challenge History</a></li>
        <li><a href='challenge.php'><span class='glyphicon glyphicon-zoom-in'></span> Challenge Detail</a></li>
        <li><a href='records.php'><span class='glyphicon glyphicon-calendar'></span> Personal Records</a></li>
        <li><a href='about.php'><span class='glyphicon glyphicon-info-sign'></span> About</a></li>
<?php
}
?>
      </ul>
      <ul class='nav navbar-nav navbar-right'>
<?php
if( $user->is_loggedin() ){
?>
        <li class='dropdown'>
          <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>
            <span class='glyphicon glyphicon-user'></span>&nbsp;Welcome <?php echo $user->uname; ?>&nbsp;<span class='caret'></span>
          </a>
          <ul class='dropdown-menu'>
            <li><a href='profile.php'><span class='glyphicon glyphicon-cog'></span>&nbsp;View Profile</a></li>
            <li><a href='logout.php?logout=true'><span class='glyphicon glyphicon-log-out'></span>&nbsp;Sign Out</a></li>
          </ul>
        </li>
<?php
}
?>
      </ul>
    </div>
  </div>
</nav>
<div class='clearfix'></div>
  <div class='container-fluid' style='margin-top:8px;'>
    <div class='container'>

<?php
if( $user->is_loggedin() ){
?>
<p>Common content for when you ARE logged in</p>
<?php
}
else{
?>
<p>Common content for when you are NOT logged in</p>
<?php
}
?>