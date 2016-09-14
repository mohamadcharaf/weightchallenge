<?php
require_once( 'session.php' );
require_once( 'common_top.php' );
?>
<script type='text/javascript'>
$( document ).ready( function(){
  $( '#invite_challenge' ).unbind( 'click' ).click( function(){
    $.post( 'invite.php' ).done( function(data){ document.write( data ); });
  });
});
</script>
<p class='h4'>Participants</p>
<hr>
<br>Show Challenge Participants
<br>Entry field to add email address to invite others
<br>ajax call to insert rows (insert into invitation table, notification table).  on success show message "added" and "click here to invite =>" mailto URL)
<br>Email URL mailto:
  <button type='button' name='btn-signup' class='btn btn-default' id='invite_challenge'>
    <i class='glyphicon glyphicon-check'></i>&nbsp;INVITE
  </button>

<?php
require_once( 'common_bottom.php' );
?>