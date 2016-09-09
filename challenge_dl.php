<?php
if( ! $trusted === 'OK' ){
  // This should be "called" as a direct include as part of challenge.php - otherwise bail
  return;
}

// Bandaid to keep things moving
$database = new Database();
$pdo = $database->dbConnection();


$sql_string = '
SELECT fk_challenge_id, start_date, end_date , start_weight, goal_weight, rank, team_size
  FROM challenge_participant
 WHERE fk_challenge_id = :challenge_id
   AND fk_user_id = :uid';
$stmt = $pdo->prepare( $sql_string );
$stmt->bindparam( ":uid", $user_id );
$stmt->bindparam( ":challenge_id", $challenge_id );
$stmt->execute();

$allData = $stmt->fetchAll( PDO::FETCH_NUM );

echo '{';
echo json_encode( $allData );
echo '}';


?>