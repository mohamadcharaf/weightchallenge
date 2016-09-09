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
    ,ajax:            'records_dl.php?user=<?php echo $user->getName() ?>&session=<?php echo $user->getSession() ?>'
    ,displayLength:   400
    ,info:            true
    ,searching:       false
    ,ordering:        false
    ,scrollY:         '300px'
    ,paging:          true
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
<p class='h4'>Personal Records</p>
<hr>
<br>This will show your entire history of weigh-ins.
<div class='personal_dt' style='width: 60%'>
  <div id='processingIndicator' >
   <img src='http://preloaders.net/preloaders/39/Funnel.gif' style='position: relative; top: 50% z-index: '>
  </div>
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