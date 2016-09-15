<?php
require_once( 'session.php' );
require_once( 'common_top.php' );
?>
<script type='text/javascript'>
$( '#notification_area' ).empty();  // Do not wait for page load to remove this content.

$( document ).ready( function(){
  var dt = $( '#table1' ).DataTable({
     processing:      true
    ,dom:             '<"toolbar">frtip'
    ,serverSide:      true
    ,ajax:            'notifications_dl.php?user=<?php echo $user->getName() ?>&session=<?php echo $user->getSession() ?>'
    ,displayLength:   400
    ,info:            true
    ,searching:       false
    ,ordering:        false
    ,scrollY:         '300px'
    ,paging:          true
  });

  $.fn.dataTable.ext.errMode = 'throw';

/**
QQQ make column msg_id hidden.
QQQ on row click, take aciton depending on msg_id.

for msg type = 1 go to page to show all challenges and invitations where user can accept/decline

 **/

});
</script>
<p class='h4'>Notifications</p>
<hr>
<div class='personal_dt' style='width: 60%'>
  <table id='table1' class='display'>
    <thead>
      <tr>
        <th>msg_id</th>
        <th>Message</th>
        <th>Date Added</th>
      </tr>
    </thead>
  </table>

</div>

<?php
require_once( 'common_bottom.php' );
?>