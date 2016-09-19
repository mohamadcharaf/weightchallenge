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
     'processing':    true
    ,'dom':           '<"toolbar">frtip'
    ,'serverSide':    true
    ,'ajax':          'history_dl.php?action=creation&user=<?php echo $user->getName() ?>&session=<?php echo $user->getSession() ?>'
    ,'displayLength': 25
    ,'info':          true
    ,'searching':     false
    ,'ordering':      false
    ,'scrollY':       '200px'
    ,'paging':        true
    ,'language':      { 'emptyTable': 'You have no pending challenges to edit.' }
    ,'columnDefs':    [ { 'targets': [ 0 ]
                       ,'visible': false }
                      ]
  });
  $( '#table0 tr' ).css( 'cursor', 'pointer' ); // This is not working.  Manually forced style on table
  $( '#table0 tbody' ).on( 'click', 'tr', function (){
    var data = dt0.row( this ).data();
    util.post( 'invite.php', {'challenge_id': data[0]} );
  });

  var dt1 = $( '#table1' ).DataTable({
     'processing':    true
    ,'dom':           '<"toolbar">frtip'
    ,'serverSide':    true
    ,'ajax':          'history_dl.php?action=participation&user=<?php echo $user->getName() ?>&session=<?php echo $user->getSession() ?>'
    ,'displayLength': 25
    ,'info':          true
    ,'searching':     false
    ,'ordering':      false
    ,'scrollY':       '200px'
    ,'paging':        true
    ,'language':      { 'emptyTable': 'You have not yet been invited any challenges.' }
    ,'columnDefs':    [{ 'targets': [ 0 ]
                          ,'visible': false }
                       ,{ 'targets':     [ 1, 2, 3, 4, 5, 6, 7, 8 ]
                         ,'createdCell': function( td, cellData, rowData, row, col ){
                                           $(td).css( { 'cursor': 'pointer' } ).unbind( 'click' ).click( function(){ util.post( 'challenge.php', { 'challenge_id': rowData[0] } ); });
                                         }}
/*
                      ,{ 'targets':     [ 3, 4, 5, 6 ]
                        ,'createdCell': function( td, cellData, rowData, row, col ){
//                                        $(td).css( { 'text-align': 'right' } ); // These two ( .css() and .addClass() ) are equivalent
                                          $(td).addClass( 'dt-right' );           // These two ( .css() and .addClass() ) are equivalent
                                        }}
*/
                       ,{ 'targets':   [ 4, 5, 6, 7 ]
                         ,'className': 'dt-right' } // This way also aligns the column title right
                       ,{ 'targets':   [ 8, 9, 10 ]
                         ,'className': 'dt-center' }
                       ,{ 'targets':     [ 9 ]
                         ,'createdCell': function( td, cellData, rowData, row, col ){
                                           if( rowData[8] == 'Invited' ){
                                             $(td).css( { 'color': 'green', 'cursor': 'pointer' } ).unbind( 'click' ).click( function(){
                                                $.ajax({
                                                  url:      'history_dl.php?action=accept&user=<?php echo $user->getName() ?>&session=<?php echo $user->getSession() ?>&challenge_id=' + rowData[0]
                                                  ,context: document.body
                                                }).done(function() {
                                                dt1.draw( false );
                                                });
                                             });
                                           }
                                          }
                        }
                       ,{ 'targets':     [ 10 ]
                         ,'createdCell': function( td, cellData, rowData, row, col ){
                                           if( rowData[8] == 'Invited' ){
                                             $(td).css( { 'color': 'red', 'cursor': 'pointer' } ).unbind( 'click' ).click( function(){
                                                $.ajax({
                                                  url:      'history_dl.php?action=decline&user=<?php echo $user->getName() ?>&session=<?php echo $user->getSession() ?>&challenge_id=' + rowData[0]
                                                  ,context: document.body
                                                }).done(function() {
                                                dt1.draw( false );
                                                });

                                             });
                                           }
                                         }}
                      ]
  });


  $( '#create_challenge' ).unbind( 'click' ).click( function(){
    window.location = 'create.php';
  });
});
</script>
<form id='fake_form' action='create.php' method='post'></form>
<div class='history_dt'>
  Challenges you've created (click to edit/view)
  <button type='button' name='btn-signup' class='btn btn-primary' id='create_challenge' style='float: right;'>
    <i class='glyphicon glyphicon-check'></i>&nbsp;NEW CHALLENGE
  </button>
  <table id='table0' class='display' cellspacing='0' width='100%' style='cursor: pointer;' >
    <thead>
      <tr>
        <th>challenge_id</th>
        <th>Challenge Name</th>
        <th>Start Date</th>
        <th>Length</th>
        <th>Challenge Type</th>
      </tr>
    </thead>
  </table>
</div>
<hr>
<div class='history_dt'>
  Challenges in which you've participated (click to view detail)
  <table id='table1' class='display' cellspacing='0' width='100%' >
    <thead>
      <tr>
        <th>challenge_id</th>
        <th>Challenge Name</th>
        <th>Start Date</th>
        <th>Length</th>
        <th>Start Weight</th>
        <th>Goal Weight</th>
        <th>Rank</th>
        <th>Team Size</th>
        <th>Status</th>
        <th>Accept</th>
        <th>Decline</th>
      </tr>
    </thead>
  </table>
</div>

<?php
require_once( 'common_bottom.php' );
?>