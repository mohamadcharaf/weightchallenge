<?php
require_once( 'session.php' );
require_once( 'common_top.php' );

$challenge_id = (isset($_REQUEST['challenge_id'])) ? htmlspecialchars( $_REQUEST['challenge_id'] ) : -1;
$trusted = 'OK';
require_once( 'challenge_dl.php' );

$start_date = $allData[0][1];
$end_date = $allData[0][2];
$start_weight = $allData[0][3];
$goal_weight = $allData[0][4];
$rank = $allData[0][5];
$team_size = $allData[0][6];

?>

<p class='h4'>Challenge Detail</p>

<?php
if( isPast( $end_date ) ){
  echo "<br>Your challenge ran between $start_date and $end_date";
  echo "<br>You began with $start_weight and a goal weight of $goal_weight";
  echo "<br>You ranked $rank of $team_size participants after reaching a final weight of NEED FINAL WEIGHT HERE.";
}
else{
  echo "<br>Your present challenge started on $start_date and will end on $end_date";
  echo "<br>Your goal weight is $goal_weight down from $start_weight";
  echo "<br>You're ranking $rank of $team_size participants with your most recent weigh in of NEED PRESENT WEIGHT HERE";
}
?>

<?php
require_once( 'common_bottom.php' );
?>