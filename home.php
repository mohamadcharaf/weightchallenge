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
</style>

<script type='text/javascript'>
var util = {};
util.update = function( rowData ){
  updateBadge( rowData[1], rowData[2], rowData[3], rowData[4], rowData[5]);
}

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
  var dt0 = $( '#table0' ).DataTable({
     'processing':    true
    ,'dom':           '<"toolbar">frtip'
    ,'serverSide':    true
    ,'ajax':          'history_dl.php?action=active&user=<?php echo $user->getName() ?>&session=<?php echo $user->getSession() ?>'
    ,'displayLength': 25
    ,'info':          true
    ,'searching':     false
    ,'ordering':      false
    ,'scrollY':       '200px'
    ,'paging':        true
    ,'language':      { 'emptyTable': 'You have not yet been invited any challenges.' }
    ,'columnDefs':    [{ 'targets': [ 0, 2, 3, 4, 5 ]
                        ,'visible': false }
                      ,{ 'targets': [ 1 ]
                         ,'createdCell': function( td, cellData, rowData, row, col ){
                                           $(td).css( { 'cursor': 'pointer' } ).unbind( 'click' ).click( function(){ util.update( rowData ); });
                                         }}
                      ]
  });
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
  <div style='width: 45%; float: left;'>
    <div id='challenge_shiny' class='pretty hide_me'>
      <div id='challenge_title' class='challenge_title'>&nbsp;</div>
      <span class='score'><span id='weight_change'>&nbsp;</span><p id='weight_result'>&nbsp;</p></span>
      <div style='margin-top: 20px;'>
        <span class='start'><p>START</p><span id='weight_start'>&nbsp;</span></span>
        <span class='goal'><p>GOAL</p><span id='weight_goal'>&nbsp;</span></span>
      </div>
    </div>
  </div>
  <div style='width: 45%; height: 200px; float: right;'>
    <div id='challenge_list'>
      <div class='history_dt' style='width: 50%; float: left'>
        Challenges in which you're participing (click to see progress)
        <table id='table0' class='display' cellspacing='0' width='100%' >
          <thead>
            <tr>
              <th>challenge_id</th>
              <th>Active Challenges</th>
              <th>challenge_progress</th>
              <th>challenge_result</th>
              <th>challenge_start</th>
              <th>challenge_goal</th>
            </tr>
          </thead>
        </table>
      </div>

    </div>
  </div></div>

<?php
require_once( 'common_bottom.php' );
?>