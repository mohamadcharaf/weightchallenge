<?php
require_once( 'session.php' );
require_once( 'common_top.php' );
?>
<script type='text/javascript'>
$( document ).ready( function(){
  var dt = $( '#table1' ).DataTable({
     'processing':    true
    ,'dom':           '<"toolbar">frtip'
    ,'serverSide':    true
    ,'ajax':          'records_dl.php?user=<?php echo $user->getName() ?>&session=<?php echo $user->getSession() ?>'
    ,'displayLength': 400
    ,'info':          true
    ,'searching':     false
    ,'ordering':      false
    ,'scrollY':       '300px'
    ,'paging':        true
    ,'language':      { 'emptyTable': 'No weigh-in data has been reported yet.' }
  });

$.fn.dataTable.ext.errMode = 'throw';

/*
  $( '#table1' ).dataTable({
    fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ){
      if( $(nRow).find( 'td:eq(1)' ).text() == 'missing' ){
        $(nRow).find('td:eq(3)').addClass( 'color' );
      }
    }
  });
*/

});
</script>
<div class='personal_dt' style='width: 60%'>
  Personal Records
  <table id='table1' class='display'>
    <thead>
      <tr>
        <th>Date</th>
        <th>Weight</th>
      </tr>
    </thead>
  </table>

</div>

<?php
require_once( 'common_bottom.php' );
?>