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
$weight_change = $allData[0][9];
$weight_message = $allData[0][10];

?>
<style type='text/css'>
.wc__noticable{
  font-weight: 300;
  font-size: 30px;
  color: #00A2D1;
}
</style>
<script type='text/javascript'>
function updateBadge( title, change, result, start, goal ){
  $( '#challenge_shiny' ).addClass( 'hide_me' ).removeClass( 'show_me' );
  $( '#challenge_title' ).text( title );
  $( '#weight_change' ).text( change );
  $( '#weight_result' ).text( result );
  $( '#weight_start' ).text( start );
  $( '#weight_goal' ).text( goal );
  $( '#challenge_shiny' ).removeClass( 'hide_me' ).addClass( 'show_me' );
}

$( document ).ready( function(){
updateBadge( '<?php echo $challenge_name; ?>', '<?php echo $weight_change; ?>', '<?php echo $weight_message; ?>', <?php echo $start_weight; ?>, <?php echo $goal_weight; ?> );
});
</script>
<?php
if( isset( $start_date ) ){
?>
  <div>
  <div style='width: 45%; float: left;'>
<?php
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
?>
  </div>
  <div style='width: 45%; height: 200px; float: right;'>
    <div id='challenge_shiny' class='pretty hide_me'>
      <div id='challenge_title' class='challenge_title'>&nbsp;</div>
      <span class='score'><span id='weight_change'>&nbsp;</span><p id='weight_result'>&nbsp;</p></span>
      <div style='margin-top: 20px;'>
        <span class='start'><p>START</p><span id='weight_start'>&nbsp;</span></span>
        <span class='goal'><p>GOAL</p><span id='weight_goal'>&nbsp;</span></span>
      </div>
    </div>
  </div>
</div>
<?php
}
else{
  echo "<br>You're not participating in a challenge right now.  Why not challenge your friends?";
}
?>

<?php
require_once( 'common_bottom.php' );
?>