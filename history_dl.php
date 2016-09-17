<?php
require( 'dbconfig.php' );
require( 'user.php' );

$uname = (isset($_REQUEST['user'])) ? $_REQUEST['user'] : null;         // Set uname to chosen user name (or null if not chosen)
$session = (isset($_REQUEST['session'])) ? $_REQUEST['session'] : null; // Set session to chosen session id (or null if not chosen)

$user = new USER( $uname, $session );
$uid = $user->getUID();

if( $uid == '' ){
  return;
}

$draw = (int) ( (isset($_REQUEST['draw'])) ? htmlspecialchars($_REQUEST['draw']) : 1 );
$start = (int) ( (isset($_REQUEST['start'])) ? htmlspecialchars($_REQUEST['start']) : 0 );
$length = (int) ( (isset($_REQUEST['length'])) ? htmlspecialchars($_REQUEST['length']) : 10 );

$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : null;  // Determine why this was called

$database = new Database();
$pdo = $database->dbConnection();

if( $action === 'creation' ){
  // Get total count
  $sql_string =  '
  SELECT COUNT(*)
    FROM wc__challenges
   WHERE fk_created_by = :uid';
  $stmt = $pdo->prepare( $sql_string );
  $stmt->bindParam( ':uid', $uid );
  $stmt->execute();
  $totalCount = $stmt->fetch( PDO::FETCH_COLUMN, 0 );

  // Get filtered count
  $filterCount = $totalCount;

  // Get the actual data for display
  $sql_string = '
     SELECT challenge_id, challenge_name, start_date, end_date, "Weight loss" AS challenge_type
       FROM wc__challenges
      WHERE fk_created_by = :uid
   ORDER BY start_date DESC
   LIMIT :start, :length';
  $stmt = $pdo->prepare( $sql_string );
  $stmt->bindParam( ':uid', $uid );
  $stmt->bindParam( ':start', $start, PDO::PARAM_INT );   // Paging support
  $stmt->bindParam( ':length', $length, PDO::PARAM_INT ); // Paging support
}
else if( $action === 'participation' ){
  // Get total count
  $sql_string = '
  SELECT COUNT(*)
    FROM wc__challenge_participant
   WHERE fk_user_id = :uid';
  if( $action === 'active' ){
    $sql_string .= ' AND status = "Participating"';
  }
  $stmt = $pdo->prepare( $sql_string );
  $stmt->bindParam( ':uid', $uid );
  $stmt->execute();
  $totalCount = $stmt->fetch( PDO::FETCH_COLUMN, 0 );

  // Get filtered count
  $filterCount = $totalCount;

  // Get the actual data for display
  $sql_string = '
     SELECT fk_challenge_id
           ,start_date
           ,end_date
           ,start_weight
           ,goal_weight
           ,rank
           ,team_size
           ,status
           ,IF( status = "Invited", "<span class=\'glyphicon glyphicon-thumbs-up\' title=\'Click to accept this challenge\'></span>", "&nbsp;" )
           ,IF( status = "Invited", "<span class=\'glyphicon glyphicon-thumbs-down\' title=\'Click to decline this challenge\'></span>", "&nbsp;" )
       FROM wc__challenge_participant
      WHERE fk_user_id = :uid';
  if( $action === 'active' ){
    $sql_string .= ' AND status = "Participating"';
  }
  $sql_string .= '
   ORDER BY start_date DESC
   LIMIT :start, :length';

  $stmt = $pdo->prepare( $sql_string );
  $stmt->bindParam( ':uid', $uid );
  $stmt->bindParam( ':start', $start, PDO::PARAM_INT );   // Paging support
  $stmt->bindParam( ':length', $length, PDO::PARAM_INT ); // Paging support
}
else if( $action === 'active' ){
  // Get total count
  $sql_string = '
  SELECT COUNT(*)
    FROM wc__challenge_participant
   WHERE fk_user_id = :uid
     AND status = "Participating"';
  $stmt = $pdo->prepare( $sql_string );
  $stmt->bindParam( ':uid', $uid );
  $stmt->execute();
  $totalCount = $stmt->fetch( PDO::FETCH_COLUMN, 0 );

  // Get filtered count
  $filterCount = $totalCount;

  // Get the actual data for display
  $sql_string = '
     SELECT c.challenge_id
           ,c.challenge_name
           ,"99"
           ,"LOST"
           ,cp.start_weight
           ,cp.goal_weight
       FROM wc__challenge_participant cp
           ,wc__challenges c
      WHERE cp.fk_user_id = :uid
        AND cp.status = "Participating"
        AND c.challenge_id = cp.fk_challenge_id
   ORDER BY c.start_date DESC
   LIMIT :start, :length';

  $stmt = $pdo->prepare( $sql_string );
  $stmt->bindParam( ':uid', $uid );
  $stmt->bindParam( ':start', $start, PDO::PARAM_INT );   // Paging support
  $stmt->bindParam( ':length', $length, PDO::PARAM_INT ); // Paging supportelse
}
else{
  // Bad value for $action.  Ignore request.
  return;
}

$stmt->execute();

$allData = $stmt->fetchAll( PDO::FETCH_NUM );

echo '{';
echo '"draw": ' . $draw;
echo ',"recordsTotal": ' . $totalCount;
echo ',"recordsFiltered": ' . $filterCount;
echo ',"data": ' . json_encode( $allData );
echo '}';

return;
?>