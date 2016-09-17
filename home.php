<?php
require_once( 'session.php' );
require_once( 'common_top.php' );

//http://jsfiddle.net/MafjT/

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
<style type='text/css'>
.wc__ticker_layout{
  font-weight: 300;
  font-size: 30px;
  color: #00A2D1;
}
.tickercontainer{
  height: 43px !important;
}
.tickercontainer .mask{
  height: 34px;
}
.hide_me{
  display: none;
  opacity: 0;
}
.show_me{
  display: visible;
  opacity: 1;
  transition: opacity 4.5s linear;
}
div.challenge_title{
  position: relative;
  top: -5px;
}
div.pretty{
  text-align: center;
  font-size: 2em;
  padding: 20px;
/*  border: 1px solid black; */
  width: 250px;
  height: 250px;
  text-align: center;
  background-color: rgb( 175, 177, 165 );
  border-radius: 10px;
}
div.pretty p{
  font-size: .5em;
  color: black;
}
span.score{
  display: inline-block;
  height: 80px;
  width: 80px;
/*  line-height: 60px; */
  border-radius: 40px;
  background-color: rgb( 250, 255, 189 );
  color: #00A2D1;
  padding-top: 10px;
}
span.start{
  display: block;
  float: left;
  border-radius: 5px;
  padding-right: 20px;
  padding-left: 20px;
  background-color: rgb( 250, 255, 189 );
  color: #00A2D1;
}
span.goal{
  display: block;
  float: right;
  border-radius: 5px;
  padding-right: 20px;
  padding-left: 20px;
  background-color: rgb( 250, 255, 189 );
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
  updateBadge( 'Dummy Title', 15, 'LOST', '200', '190' );
});
</script>

<div id='notification_area' class='tickercontainer'>
  <ul id='notification_ticker' class='newsticker'>
<?php
$msgNum = 0;
if( isset( $notifications ) ){
  if( count( $notifications ) > 5 ){
    $msgNum++;
    echo "<li class='wc__ticker_layout' data-update='item{$msgNum}'>You have many notifications!  Click here to manage them.</p></li>";
  }
  foreach( $notifications as $text ){
    $msgNum++;
    echo "<li class='wc__ticker_layout' data-update='item{$msgNum}'>{$text[0]}</p></li>";
  }
}
?>
  </ul>
</div>

<div style='margin-top: 50px;'>
  <div style='width: 45%; /*height: 200px;*/ float: left; /*border: 1px solid red;*/'>
    <div id='challenge_shiny' class='pretty hide_me'>
      <div id='challenge_title' class='challenge_title'>&nbsp;</div>
      <span class='score'><span id='weight_change'>&nbsp;</span><p id='weight_result'>&nbsp;</p></span>
      <div style='margin-top: 20px;'>
        <span class='start'><p>START</p><span id='weight_start'>&nbsp;</span></span>
        <span class='goal'><p>GOAL</p><span id='weight_goal'>&nbsp;</span></span>
      </div>
    </div>
  </div>
  <div style='width: 45%; height: 200px; float: right;border: 1px solid red;'>&nbsp;
    <div id='challenge_list'>
    </div>
  </div></div>

<?php
require_once( 'common_bottom.php' );
?>