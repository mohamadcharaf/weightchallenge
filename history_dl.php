<?php
// Needs a security token to verify call is from session
// This is the data layer behind the history UI
require( 'dbconfig.php' );

$draw = (isset($_REQUEST['draw'])) ? $_REQUEST['draw'] : 1;
$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
$length = (isset($_REQUEST['length'])) ? $_REQUEST['length'] : 10;
//if( $length == -1 ){ $length = count( $foo_json['data'] ); }
$search = (isset($_REQUEST['search'])) ? $_REQUEST['search'] : null;

// Bandaid to keep things moving
$database = new Database();
$pdo = $database->dbConnection();

// QQQ Presently hard coded to 1, later use the passed in user id
$uid = 1;

// Get total count
$sql_string =  '
SELECT COUNT(*)
  FROM challenge_participant
 WHERE fk_user_id = :uid';
$stmt = $pdo->prepare( $sql_string );
$stmt->bindparam( ":uid", $uid );
$stmt->execute();
$totalCount = $stmt->fetch( PDO::FETCH_COLUMN, 0 );


// Get filtered count
/**
$sql_string =  '
SELECT COUNT(*)
  FROM challenge_participant
 WHERE fk_user_id = :uid
   AND .....';
$stmt = $pdo->prepare( $sql_string );
$stmt->bindparam( ":uid", $uid );
... filter rules here ...
$stmt->execute();
$filterCount = $stmt->fetch( PDO::FETCH_COLUMN, 0 );
 **/
$filterCount = $totalCount;

// Ordering
$sql_order = ' ORDER BY fk_challenge_id ASC ';

// Paging support
// Turn these into bind variables
$sql_limit = ' LIMIT '.intval( $start ).', '.  intval( $length ) . ' ';

// Get the actual data for display
$sql_string = '
   SELECT fk_challenge_id, start_date, end_date , start_weight, goal_weight, rank, team_size
     FROM challenge_participant
    WHERE fk_user_id = :uid
 ORDER BY start_date DESC
 LIMIT :start, :length';

$stmt = $pdo->prepare( $sql_string );
$stmt->bindparam( ":uid", $uid );
$stmt->bindparam( ":start", intval($start), PDO::PARAM_INT );   // Paging support
$stmt->bindparam( ":length", intval($length), PDO::PARAM_INT ); // Paging support
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