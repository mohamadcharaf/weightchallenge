<?php
require_once( 'session.php' );
require_once( 'common_top.php' );
?>
<script type='text/javascript'>
var util = {};
util.post = function( url, fields ){
  var fake_form = $( '#fake_form' );
  fake_form[0].action = url;
  $.each( fields, function( key, val ){
     $( '<input>' ).attr({
        type:  'hidden'
       ,name:  key
       ,value: val
     }).appendTo( fake_form );
  });
  $( '#fake_form' ).submit();
}

$( document ).ready( function(){
  var dt0 = $( '#table0' ).DataTable({
     processing:      true
    ,dom:             '<"toolbar">frtip'
    ,serverSide:      true
    ,ajax:            'history_dl.php?action=creation&user=<?php echo $user->getName() ?>&session=<?php echo $user->getSession() ?>'
    ,displayLength:   25
    ,info:            true
    ,searching:       false
    ,ordering:        false
    ,scrollY:         '200px'
    ,paging:          true
    ,columnDefs:      [{
       targets:  [ 0 ]
      ,visible: false
     }]
  });
  $( '#table0 tr' ).css( 'cursor', 'pointer' ); // This is not working.  Manually forced style on table
  $( '#table0 tbody' ).on( 'click', 'tr', function (){
    var data = dt0.row( this ).data();
    util.post( 'invite.php', {'challenge_id': data[0]} );
  });

  var dt1 = $( '#table1' ).DataTable({
     processing:      true
    ,dom:             '<"toolbar">frtip'
    ,serverSide:      true
    ,ajax:            'history_dl.php?action=participation&user=<?php echo $user->getName() ?>&session=<?php echo $user->getSession() ?>'
    ,displayLength:   25
    ,info:            true
    ,searching:       false
    ,ordering:        false
    ,scrollY:         '200px'
    ,paging:          true
    ,columnDefs:      [{
       targets:  [ 0 ]
      ,visible: false
     }]
  });

  $( '#table1 tr' ).css( 'cursor', 'pointer' ); // This is not working.  Manually forced style on table
  $( '#table1 tbody' ).on( 'click', 'tr', function (){
    var data = dt1.row( this ).data();
    util.post( 'challenge.php', {'challenge_id': data[0]} );
  });

  $( '#create_challenge' ).unbind( 'click' ).click( function(){
    window.location = 'create.php';
  });
});
</script>
<form id='fake_form' action='XXXXXX.php' method='post'></form>
<p class='h4'>Challenge</p>
<hr>

<div class='history_dt'>
  Challenges you've created (click to edit/view)
  <button type='button' name='btn-signup' class='btn btn-default' id='create_challenge' style='float: right;'>
    <i class='glyphicon glyphicon-check'></i>&nbsp;NEW CHALLENGE
  </button>

  <table id='table0' class='display' cellspacing='0' width='100%' style='cursor: pointer;' >
    <thead>
      <tr>
        <th>challenge_id</th>
        <th>Challenge Name</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Challenge Type</th>
      </tr>
    </thead>
  </table>
</div>

<div class='history_dt'>
  Challenges in which you've participated (click to view detail)
  <table id='table1' class='display' cellspacing='0' width='100%' style='cursor: pointer;' >
    <thead>
      <tr>
        <th>challenge_id</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Start Weight</th>
        <th>Goal Weight</th>
        <th>Rank</th>
        <th>Team Size</th>
        <th>Status</th>
      </tr>
    </thead>
  </table>
</div>

<?php
require_once( 'common_bottom.php' );
?>