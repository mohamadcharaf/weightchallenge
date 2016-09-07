<?php
require_once( 'session.php' );
require_once( 'common_top.php' );
?>
<script>
$( document ).ready( function(){
  var dt = $( '#challenge_table' ).on( 'processing.dt'
             ,function( e, settings, processing ){
              $( '#processingIndicator' ).css( 'display', processing ? 'block' : 'none' );
             }).DataTable({
     processing:      true
    ,dom:             '<"toolbar">frtip'
    ,serverSide:      true
    ,ajax:            'history_dl.php'
    ,displayLength:   25
    ,info:            true
    ,searching:       true
//    ,searchDelay:     2000
    ,ordering:        false
    ,scrollY:         '200px'
    ,paging:          true
  });

});
</script>
<p class="h4">History Page</p>
<hr>
<br>This will show your old (completed) challenges
<br>Clicking on an old one will take you to the Challenge Detail page.
<div class='history_dt'>
  <div id='processingIndicator' >
   <img src='http://preloaders.net/preloaders/39/Funnel.gif' style='position: relative; top: 50% z-index: '>
  </div>
  Challenge History

  <table id='challenge_table' class='display' cellspacing='0' width='100%'>
    <thead>
      <tr>
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