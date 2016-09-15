<?php
require_once( 'session.php' );
require_once( 'common_top.php' );
?>

<?php
if( isset( $_POST['btn-create'] ) ){
  $cname = strip_tags( $_POST['txt_cname'] );
  $cstart = strip_tags( $_POST['txt_start'] );
  $cend = strip_tags( $_POST['txt_end'] );

  if( $cname == '' ){
    $error[] = 'provide challenge name';
  }
  if( $cstart == '' ){
    $error[] = 'provide challenge start date';
  }
  if( $cend == '' ){
    $error[] = 'provide challenge end date';
  }

  // Go do the DB work to create the challenge (and return 'created'  or error)
  // challenge_id takes care of itself
  // challenge_type is only one type for now.
  try{
    $sql = 'INSERT INTO wc__challenges( fk_created_by, challenge_name, start_date, end_date, challenge_type )
                 VALUES( :fk_created_by, :challenge_name, :start_date, :end_date, 1 )';
    $stmt = $user->prepQuery( $sql );
    $stmt->bindParam( ':fk_created_by', $user->getUID() );
    $stmt->bindParam( ':challenge_name', $cname );
    $stmt->bindParam( ':start_date', $cstart );
    $stmt->bindParam( ':end_date', $cend);
    if( $stmt->execute() ){
      $lastId = $user->lastInsertId();
      $sql = 'INSERT INTO wc__challenge_participant( fk_challenge_id, fk_user_id, start_date, end_date, challenge_type, status )
                   VALUES( :fk_challenge_id, :fk_user_id, :start_date, :end_date, 1, "Invited" )';

      $stmt = $user->prepQuery( $sql );
      $stmt->bindParam( ':fk_challenge_id', $lastId );
      $stmt->bindParam( ':fk_user_id', $user->getUID() );
      $stmt->bindParam( ':start_date', $cstart );
      $stmt->bindParam( ':end_date', $cend);
      if( $stmt->execute() ){
        // Success
        $user->redirect( 'create.php?created' );
      }
      else{
        // Failure to join challenge
        $error[] = 'Challenge created, but unable to join';
      }
    }
    else{
      // Failure to create challenge
      $error[] = 'Challenge not created';
    }
  }
  catch( Exception $e ){
    $error[] = 'Some very bad DB juju';
  }

}
else{
}

?>

<script type='text/javascript'>
$( document ).ready( function(){

// jQuery UI version
  $( '.datepicker' ).datepicker({
     dateFormat: 'yy-mm-dd'
    ,changeMonth: true
    ,changeYear: true
    ,numberOfMonths: [ 1, 3 ]
    ,minDate    : '+1d'
  });

  $( '#invite_challenge' ).unbind( 'click' ).click( function(){
    $.post( 'invite.php' ).done( function(data){ document.write( data ); });
  });
});
</script>
<p class='h4'>Create a Challenge</p>
<hr>
<div class='create-form' style='width: 50%'>
  <form method='post' class='form-create'>
<?php
if( isset( $error ) ){
  foreach( $error as $error ){
?>
    <div class='alert alert-danger'>
      <i class='glyphicon glyphicon-warning-sign'></i> &nbsp; <?php echo $error; ?> !
    </div>
<?php
  }
}
else if( isset( $_GET['created'] ) ){
// Better idea.  Make a page that displays details for one challenge.
// Send user there when success
// That page will display the invite button if the end date is not past
// That page will display a delete button if the start date is not psat
?>
    <div class='alert alert-info'>
      <i class='glyphicon glyphicon-log-in'></i> &nbsp; Challenge successfully created.
    </div>
<br>Button to take user to invite page
  <button type='button' name='btn-invite' class='btn btn-default' id='invite_challenge'>
    <i class='glyphicon glyphicon-check'></i>&nbsp;INVITE OTHERS
  </button>

<?php
}
?>
    <div class='form-group'>
      <input type='text' class='form-control' name='txt_cname' placeholder='Enter challenge name' value='<?php if(isset($error)){echo $cname;}?>' />
    </div>
    <div class='form-group'>
      <div class='input-group'>
        <input type='text' class='form-control datepicker' name='txt_start' id='txt_start' placeholder='Enter challenge start date' value='<?php if(isset($error)){echo $cstart;}?>' />
        <div class='input-group-addon'>
          <span class='glyphicon glyphicon-calendar'></span>
        </div>
      </div>
    </div>

    <div class='form-group'>
      <div class='input-group'>
        <input type='text' class='form-control datepicker' name='txt_end' id='txt_end' placeholder='Enter challenge end date' value='<?php if(isset($error)){echo $cend;}?>' />
        <div class='input-group-addon'>
          <span class='glyphicon glyphicon-calendar'></span>
        </div>
      </div>
    </div>

    <div class='clearfix'></div>
    <hr />
    <div class='form-group'>
      <button type='submit' name='btn-create' class='btn btn-default'>
        <i class='glyphicon glyphicon-check'></i>&nbsp;CREATE
      </button>
    </div>
  </form>
</div>

<?php
require_once( 'common_bottom.php' );
?>