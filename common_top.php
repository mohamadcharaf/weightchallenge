<?php
require_once( 'user.php' );
$user = new USER();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Hello World</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <script type="text/javascript" src="jquery-3.1.0.min.js"></script>
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
  <link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
  <link rel="stylesheet" href="style.css" type="text/css"  />
?>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
<?php
if( $user->is_loggedin() ){
?>
        <li><a href="home.php"><span class="glyphicon glyphicon-home"></span> home</a></li>
        <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> profile</a></li>
<?php
}
?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
<?php
if( $user->is_loggedin() ){
?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <span class="glyphicon glyphicon-user"></span>&nbsp;Welcome <?php echo $user->uname; ?>&nbsp;<span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li><a href="#"><span class="glyphicon glyphicon-user"></span>&nbsp;View Profile</a></li>
            <li><a href="logout.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Sign Out</a></li>
          </ul>
        </li>
<?php
}
?>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav>
<div class="clearfix"></div>
  <div class="container-fluid" style="margin-top:80px;">
    <div class="container">

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