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

/**
  * Look for and "repair" missing data.
  * Repair means:
  * Each day must have one and only one weight in.  THIS IS TAKEN CARE OF IN THE CODE THAT INSERTS WEIGHTS
  * If more than one, keep the final one.
  *  This SQL finds the date with DUPLICATES
  *  SELECT DATE(weigh_date), COUNT(1) FROM wc__user_weigh_in WHERE fk_user_id = 1 GROUP BY DATE(weigh_date) HAVING COUNT(1) > 1;
  * If a day is a missing row, insert a row with that date and NULL as weight
  *  This SQL finds the MISSING data (but only if the duplcates have already been removed)
  * SELECT dt
  *   FROM wc__calendar_table
  *  WHERE dt NOT IN ( SELECT DATE(weigh_date)
  *                      FROM wc__user_weigh_in
  *           WHERE fk_user_id = 1)
  *    AND dt BETWEEN ( SELECT DATE(MIN(weigh_date))
  *                       FROM wc__user_weigh_in
  *            WHERE fk_user_id = 1) AND NOW();
  *
  * This requires the toolkit table "calendar_table"
 **/

// Fix missing data (if any)
$sql_string =  '
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight )
SELECT :uid, dt, NULL
  FROM wc__calendar_table
 WHERE dt NOT IN ( SELECT DATE(weigh_date)
                     FROM wc__user_weigh_in
          WHERE fk_user_id = :uid)
   AND dt BETWEEN ( SELECT DATE(MIN(weigh_date))
                      FROM wc__user_weigh_in
           WHERE fk_user_id = :uid) AND NOW()';
$stmt = $pdo->prepare( $sql_string );
$stmt->bindParam( ':uid', $uid );
$stmt->execute();

// Get total count
$sql_string =  '
SELECT COUNT(*)
  FROM wc__user_weigh_in
 WHERE fk_user_id = :uid';
$stmt = $pdo->prepare( $sql_string );
$stmt->bindParam( ':uid', $uid );
$stmt->execute();
$totalCount = $stmt->fetch( PDO::FETCH_COLUMN, 0 );


// Get filtered count (initial release does not support user filtering)
/**
$sql_string = '
SELECT COUNT(*)
  FROM wc__user_weigh_in
 WHERE fk_user_id = :uid';
$stmt = $pdo->prepare( $sql_string );
$stmt->bindParam( ':uid', $uid );
$stmt->execute();
$filterCount = $stmt->fetch( PDO::FETCH_COLUMN, 0 );
 **/
$filterCount = $totalCount;

// Get the actual data for display
$sql_string = '
   SELECT DATE(weigh_date), IFNULL( weight, "missing" )
     FROM wc__user_weigh_in
 WHERE fk_user_id = :uid
 ORDER BY weigh_date DESC
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