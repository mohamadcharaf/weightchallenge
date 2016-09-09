<?php
// Needs a security token to verify call is from session
// This is the data layer behind the history UI
require( 'dbconfig.php' );
require( 'user.php' );

$uname = (isset($_REQUEST['user'])) ? $_REQUEST['user'] : null;         // Set uname to chosen user name (or null if not chosen)
$session = (isset($_REQUEST['session'])) ? $_REQUEST['session'] : null; // Set session to chosen session id (or null if not chosen)
//QQQ Do a test...  Log in, then with phpMyAdmin mess up the session id.  Then hit Refresh button on Home page.
//QQQ The expected result is that you'll be denied access.

$user = new USER( $uname, $session );
$uid = $user->getUID();

if( $uid == '' ){
  return;
}

$draw = (int) (isset($_REQUEST['draw'])) ? htmlspecialchars($_REQUEST['draw']) : 1;
$start = (int) (isset($_REQUEST['start'])) ? htmlspecialchars($_REQUEST['start']) : 0;
$length = (int) (isset($_REQUEST['length'])) ? htmlspecialchars($_REQUEST['length']) : 10;
//if( $length == -1 ){ $length = count( $foo_json['data'] ); }
//$search = (isset($_REQUEST['search'])) ? $_REQUEST['search'] : null;

// Bandaid to keep things moving
$database = new Database();
$pdo = $database->dbConnection();

// Get total count
$sql_string =  '
SELECT COUNT(*)
  FROM wc__challenge_participant
 WHERE fk_user_id = :uid';
$stmt = $pdo->prepare( $sql_string );
$stmt->bindParam( ":uid", $uid );
$stmt->execute();
$totalCount = $stmt->fetch( PDO::FETCH_COLUMN, 0 );


// Get filtered count
/**
$sql_string =  '
SELECT COUNT(*)
  FROM wc__challenge_participant
 WHERE fk_user_id = :uid
   AND .....';
$stmt = $pdo->prepare( $sql_string );
$stmt->bindParam( ":uid", $uid );
... filter rules here ...
$stmt->execute();
$filterCount = $stmt->fetch( PDO::FETCH_COLUMN, 0 );
 **/
$filterCount = $totalCount;

// Get the actual data for display
$sql_string = '
   SELECT fk_challenge_id, start_date, end_date , start_weight, goal_weight, rank, team_size
     FROM wc__challenge_participant
    WHERE fk_user_id = :uid
 ORDER BY start_date DESC
 LIMIT :start, :length';

$stmt = $pdo->prepare( $sql_string );
$stmt->bindParam( ':uid', $uid );
//$stmt->bindParam( ':start', intval($start), PDO::PARAM_INT );   // Paging support
//$stmt->bindParam( ':length', intval($length), PDO::PARAM_INT ); // Paging support
$stmt->bindParam( ':start', $start );   // Paging support
$stmt->bindParam( ':length', $length ); // Paging support
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