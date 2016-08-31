<?php
require_once( 'session.php' );

require_once( 'user.php' );
$auth_user = new USER();

$user_id = $_SESSION['user_session'];

$stmt = $auth_user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
$stmt->execute(array(":user_id"=>$user_id));

$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

require_once( 'common_top.php' );
?>
<p class="h4">User Home Page</p>
<?php
require_once( 'common_bottom.php' );
?>