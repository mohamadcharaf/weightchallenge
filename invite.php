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
// QQQ And it seems to be inserting two anyway.
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

$sql_string =  '
SELECT user_name, user_email
  FROM wc__users
 WHERE user_id IN ( SELECT fk_user_id
                      FROM wc__challenge_participant
                     WHERE fk_challenge_id = :cid )';
$stmt = $user->prepQuery( $sql_string );
$stmt->bindParam( ':cid', $challenge_id );
$stmt->execute();
$participants = $stmt->fetchAll( PDO::FETCH_NUM );
?>

<style>
table.participants td, table.participants th{
  padding: 1px 10px;
}
</style>

For <?php echo $challengeName; ?> there are <?php echo count( $participants ); ?> participants
<?php
$playerNum = 0;
if( isset( $participants ) ){
  echo '<table class="participants" style="width: 45%;">';
    echo "<tr><th style='width: 5%;'>Participant</th><th style='width: 95%;'>Name</th></tr>";
  foreach( $participants as $person ){
    $playerNum++;
    echo "<tr><td style='text-align: right;'>{$playerNum}</td><td><a href='mailto:{$person[1]}?Subject=You have been invited to a challenge!&body=Click here to join (NEED embedded URL)' title='Click here to open your email client to send an invitation'>{$person[0]}</a></td></tr>";
  }
  echo '</table>';
}
?>


<div class='signin-form'>
  <form method='post' class='form-signin'>
    <h2 class='form-signin-heading'>Invite friends to participate</h2>
    <hr />
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
      <button type='submit' name='btn-invite' class='btn btn-default' id='invite_challenge'>
        <i class='glyphicon glyphicon-check'></i>&nbsp;INVITE
      </button>
    </div>
  </form>
</div>


<?php
require_once( 'common_bottom.php' );
?>