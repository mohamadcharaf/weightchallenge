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
$challenge_name = $allData[0][7];
$recent_weight = $allData[0][8];

?>
<style type='text/css'>
.wc__noticable{
  font-weight: 300;
  font-size: 30px;
  color: #00A2D1;
}
</style>
<?php
if( isset( $start_date ) ){
  echo "<span class='wc__noticable'>{$challenge_name}</span>";
  if( isPast( $end_date ) ){
    echo "<br>Your challenge ran between $start_date and $end_date";
    echo "<br>You began with $start_weight and a goal weight of $goal_weight";
    echo "<br>You ranked $rank of $team_size participants after reaching a final weight of {$recent_weight}";
  }
  else{
    echo "<br>Your present challenge started on $start_date and will end on $end_date";
    echo "<br>Your goal weight is $goal_weight down from $start_weight";
    echo "<br>You're ranking $rank of $team_size participants with your most recent weigh in of {$recent_weight}";
  }
}
else{
  echo "<br>You're not participating in a challenge right now.  Why not challenge your friends?";
}
?>

<?php
require_once( 'common_bottom.php' );
?>