<?php
require_once( 'session.php' );
require_once( 'common_top.php' );

$challenge_id = (isset($_REQUEST['challenge_id'])) ? htmlspecialchars( $_REQUEST['challenge_id'] ) : -1;

//QQQ verify that the present user is the one that created the challenge, otherwise silently jump to the home page

if( isset( $_POST['btn-invite'] ) ){
  $email = strip_tags( $_POST['txt_email'] );
  if( $email == '' ){
    $error[] = 'provide email id';
  }
  else if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ){
    $error[] = 'Please enter a valid email address';
  }
  else{
    try{
      $sql_string = '
      SELECT user_id
        FROM wc__users
       WHERE user_email = :email';
      $stmt = $user->prepQuery( $sql_string );
      $stmt->bindParam( ':email', $email );
      $stmt->execute();

// QQQ Hmm, this needs to be a unique key to prevent double inserts.
      $row = $stmt->fetch( PDO::FETCH_ASSOC );
      $invitee_id = $row[ 'user_id' ];
      $sql_string = '
        INSERT INTO wc__challenge_participant( fk_challenge_id, fk_user_id, start_date, end_date, challenge_type, status )
        SELECT challenge_id, :invitee_id, start_date, end_date, challenge_type, "Invited"
          FROM wc__challenges
         WHERE challenge_id = :cid';
      $stmt = $user->prepQuery( $sql_string );
      $stmt->bindParam( ':invitee_id', $invitee_id );
      $stmt->bindParam( ':cid', $challenge_id );
      $stmt->execute();

      $sql_string = '
        UPDATE wc__challenge_participant main
        INNER JOIN (
            SELECT fk_challenge_id, count(1) c
            FROM wc__challenge_participant
            WHERE fk_challenge_id = :cid
        ) counter ON main.fk_challenge_id = counter.fk_challenge_id
        SET main.team_size = counter.c';
      $stmt->bindParam( ':cid', $challenge_id );
      $stmt->execute();

    }
    catch( PDOException $e ){
      // Look for the no rows found error and suggest they invite thier friend to sign up for the program
      $error[] = "An error prevented you from inviting that person.  Perhaps they are not yet participating.  Please invite them to join by clicking <a href='mailto:{$email}?Subject=You have been invited to join Weightloss Challenge!&body=Visit http://link_here to join'>HERE</a>";
    }
  }
}

$sql_string =  '
SELECT challenge_name
  FROM wc__challenges
 WHERE challenge_id = :cid';
$stmt = $user->prepQuery( $sql_string );
$stmt->bindParam( ':cid', $challenge_id );
$stmt->execute();
$challengeName = $stmt->fetch( PDO::FETCH_COLUMN, 0 );
?>

<script type='text/javascript'>
$( document ).ready( function(){
  var dt0 = $( '#table0' ).DataTable({
     'processing':    true
    ,'dom':           '<"toolbar">frtip'
    ,'serverSide':    true
    ,'ajax':          'history_dl.php?action=invited&user=<?php echo $user->getName() ?>&session=<?php echo $user->getSession() ?>&challenge_id=<?php echo $challenge_id ?>'
    ,'displayLength': 25
    ,'info':          true
    ,'searching':     false
    ,'ordering':      false
    ,'scrollY':       '200px'
    ,'paging':        true
    ,'language':      { 'emptyTable': 'You have not yet invited any participants.' }
    ,'columnDefs':    [{ 'targets': [ 1 ]
                        ,'visible': false }
                      ,{ 'targets': [ 0 ]
                         ,'createdCell': function( td, cellData, rowData, row, col ){
                                           $(td).css( { 'cursor': 'pointer' } );
                                         }
                         ,'render':      function( data, type, full, meta ){
                                            return '<a href="mailto:' + full[1] + '?Subject=You have been invited to a challenge!&body=Click here to join (NEED embedded URL)" target="_blank">' + data + '</a>';
                                         }}
                      ]
  });
});
</script>

<h2>Manage Challenge: <?php echo $challengeName; ?></h2>

<div style='margin-top: 50px;'>
  <div style='width: 45%; float: left;'>
    <table id='table0' class='display' cellspacing='0' width='100%' >
      <thead>
        <tr>
          <th>Click person to send invitation email</th>
          <th>email</th>
        </tr>
      </thead>
    </table>
  </div>
  <div style='width: 45%; height: 200px; float: right;'>
    <div class='signin-form' style='margin-top: 0px;'>
      <form method='post' class='form-signin'>
        <h2 class='form-signin-heading'>Invite friends to participate</h2>
        <hr>
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
//else if( isset( $_GET['joined'] ) ){
?>
<!--
    <div class='alert alert-info'>
      Successfully added to the challenge.
    </div> -->
<?php
//}
?>
        <div class='form-group'>
          <input type='text' class='form-control' name='txt_email' placeholder='Enter E-Mail address' value='<?php if(isset($error)){echo $email;}?>' />
        </div>
        <div class='form-group'>
          <input type='hidden' name='challenge_id' value='<?php echo $challenge_id;?>'>
          <button type='submit' name='btn-invite' class='btn btn-primary' id='invite_challenge'>
            <i class='glyphicon glyphicon-check'></i>&nbsp;INVITE
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


<?php
require_once( 'common_bottom.php' );
?>