<?php
require_once( 'session.php' );
require_once( 'common_top.php' );
?>
<script>
$( document ).ready( function(){
  var dt = $( '#table1' ).on( 'processing.dt'
             ,function( e, settings, processing ){
              $( '#processingIndicator' ).css( 'display', processing ? 'block' : 'none' );
             }).DataTable({
     processing:      true
    ,dom:             '<"toolbar">frtip'
    ,serverSide:      true
    ,ajax:            'history_dl.php?user=<?php echo $user->getName() ?>&session=<?php echo $user->getSession() ?>'
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

  $( '#table1 tr' ).css( 'cursor', 'pointer' );
  $( '#table1 tbody' ).on( 'click', 'tr', function (){
    var data = dt.row( this ).data();
//    window.location = 'challenge.php?challenge_id=' + data[0];
// Yes, this is a screwy way to do it.  And it looks odd in the URL.  But whatever, it's fun.
    $.post( 'challenge.php', {'challenge_id': data[0]} ).done( function(data){ document.write( data ); });
  });
});
</script>
<p class='h4'>History Page</p>
<hr>
<br>This will show your old (completed) challenges
<br>Clicking on an old one will take you to the Challenge Detail page.
<div class='history_dt'>
  <div id='processingIndicator' >
   <img src='http://preloaders.net/preloaders/39/Funnel.gif' style='position: relative; top: 50% z-index: '>
  </div>
  Challenge History

  <table id='table1' class='display' cellspacing='0' width='100%'>
    <thead>
      <tr>
        <th>challenge_id</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Start Weight</th>
        <th>Goal Weight</th>
        <th>Rank</th>
        <th>Team Size</th>
      </tr>
    </thead>
  </table>

</div>

<?php
require_once( 'common_bottom.php' );
?>