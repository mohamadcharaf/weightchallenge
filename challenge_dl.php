<?php
if( $trusted !== 'OK' ){
  // This should be "called" as a direct include as part of challenge.php - otherwise bail
  return;
}
// Since this is "trusted" and challenge.php has already included common_top.php there is a $user variable.

$sql_string = null;
$uid = (int) ( $user->getUID() );

if( $challenge_id == -1 ){
  $sql_string = '
  SELECT fk_challenge_id, DATE(start_date), DATE(end_date) , start_weight, goal_weight, rank, team_size
    FROM wc__challenge_participant
   WHERE end_date = (SELECT MAX(end_date) FROM wc__challenge_participant WHERE fk_user_id = :uid )
     AND fk_user_id = :uid';

  $stmt = $user->prepQuery( $sql_string );
  $stmt->bindParam( ':uid', $uid, PDO::PARAM_INT );
}
else{
  $sql_string = '
  SELECT fk_challenge_id, DATE(start_date), DATE(end_date) , start_weight, goal_weight, rank, team_size
    FROM wc__challenge_participant
   WHERE fk_challenge_id = :challenge_id
     AND fk_user_id = :uid';

  $stmt = $user->prepQuery( $sql_string );
  $stmt->bindParam( ':uid', $uid, PDO::PARAM_INT );
  $stmt->bindParam( ':challenge_id', $challenge_id );
}

$stmt->execute();

$allData = $stmt->fetchAll( PDO::FETCH_NUM );

?>