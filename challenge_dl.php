<?php
if( $trusted !== 'OK' ){
  // This should be "called" as a direct include as part of challenge.php - otherwise bail
  return;
}
// Since this is "trusted" and challenge.php has already included common_top.php there is a $user variable.

$sql_string = null;
$uid = (int) ( $user->getUID() );

if( $challenge_id == -1 ){
/** Original SQL (with addition to not show future challenges)
  SELECT fk_challenge_id, DATE(start_date), DATE(end_date) , start_weight, goal_weight, rank, team_size
    FROM wc__challenge_participant
   WHERE end_date = (SELECT MAX(end_date) FROM wc__challenge_participant WHERE fk_user_id = :uid AND start_date <= NOW() )
     AND fk_user_id = :uid
 **/
  $sql_string = '
SELECT fk_challenge_id
      ,start_date
      ,end_date
      ,start_weight
      ,goal_weight
      ,rank
      ,team_size
      ,challenge_name
      ,weight
      ,IF( weight > start_weight, start_weight - weight, weight - start_weight )
      ,IF( weight > start_weight, "GAINED", "LOST" )
  FROM( SELECT fk_challenge_id
              ,start_date
              ,end_date
              ,start_weight
              ,goal_weight
              ,rank
              ,team_size
              ,(SELECT c.challenge_name
                  FROM wc__challenges c
                 WHERE c.challenge_id = fk_challenge_id ) AS challenge_name
              ,(SELECT uwi.weight
                 FROM wc__user_weigh_in uwi
                WHERE uwi.fk_user_id = :uid
                  AND uwi.weigh_date = ( SELECT max(weigh_date)
                                           FROM wc__user_weigh_in
                                          WHERE fk_user_id = :uid
                                            AND weight IS NOT null
                                            AND weigh_date BETWEEN start_date AND end_date )) AS weight
          FROM wc__challenge_participant
         WHERE fk_user_id = :uid
           AND status = "Participating" ) box';

  $stmt = $user->prepQuery( $sql_string );
  $stmt->bindParam( ':uid', $uid, PDO::PARAM_INT );
}
else{
  $sql_string = '
SELECT fk_challenge_id
      ,start_date
      ,end_date
      ,start_weight
      ,goal_weight
      ,rank
      ,team_size
      ,challenge_name
      ,weight
      ,IF( weight > start_weight, start_weight - weight, weight - start_weight )
      ,IF( weight > start_weight, "GAINED", "LOST" )
  FROM( SELECT fk_challenge_id
              ,start_date
              ,end_date
              ,start_weight
              ,goal_weight
              ,rank
              ,team_size
              ,(SELECT challenge_name FROM wc__challenges WHERE challenge_id = fk_challenge_id ) AS challenge_name
              ,(SELECT uwi.weight
                 FROM wc__user_weigh_in uwi
                WHERE uwi.fk_user_id = :uid
                  AND uwi.weigh_date = ( SELECT max(weigh_date)
                                           FROM wc__user_weigh_in
                                          WHERE fk_user_id = :uid
                                            AND weight IS NOT null
                                            AND weigh_date BETWEEN start_date AND end_date )) AS weight
          FROM wc__challenge_participant
         WHERE fk_challenge_id = :challenge_id
           AND fk_user_id = :uid ) box';

  $stmt = $user->prepQuery( $sql_string );
  $stmt->bindParam( ':uid', $uid, PDO::PARAM_INT );
  $stmt->bindParam( ':challenge_id', $challenge_id );
}

$stmt->execute();

$allData = $stmt->fetchAll( PDO::FETCH_NUM );

?>