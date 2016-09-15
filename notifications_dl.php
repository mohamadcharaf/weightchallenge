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

$database = new Database();
$pdo = $database->dbConnection();

// Get total count
$sql_string =  '
SELECT COUNT(*)
  FROM wc__notifications
 WHERE fk_user_id = :uid';
$stmt = $pdo->prepare( $sql_string );
$stmt->bindParam( ':uid', $uid );
$stmt->execute();
$totalCount = $stmt->fetch( PDO::FETCH_COLUMN, 0 );


// Get filtered count (initial release does not support user filtering)
$filterCount = $totalCount;

// Get the actual data for display
$sql_string = '
   SELECT msg_id, msg_text, DATE(added_on)
     FROM wc__notifications
 WHERE fk_user_id = :uid
 ORDER BY added_on ASC
 LIMIT :start, :length';

$stmt = $pdo->prepare( $sql_string );
$stmt->bindParam( ':uid', $uid );
$stmt->bindParam( ':start', $start, PDO::PARAM_INT );   // Paging support
$stmt->bindParam( ':length', $length, PDO::PARAM_INT ); // Paging support
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