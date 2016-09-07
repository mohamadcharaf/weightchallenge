<?php
// Needs a security token to verify call is from session
// This is the data layer behind the history UI
require( 'dbconfig.php' );

$draw = (isset($_REQUEST['draw'])) ? $_REQUEST['draw'] : 1;
$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
$length = (isset($_REQUEST['length'])) ? $_REQUEST['length'] : 10;
if( $length == -1 ){ $length = count( $foo_json['data'] ); }
$search = (isset($_REQUEST['search'])) ? $_REQUEST['search'] : null;

// Bandaid to keep things moving
$database = new Database();
$pdo = $database->dbConnection();

// Filtering
$sql_filter = ' WHERE fk_user_id = 1 '; // Presently hard coded to 1, later use the passed in user id

// Get total count
$stmt = $pdo->prepare( 'SELECT COUNT(*) FROM challenges WHERE challenge_id IN ( SELECT fk_challenge_id FROM challenge_participant ' . $sql_filter . ')' );
$stmt->execute();
$totalCount = $stmt->fetch( PDO::FETCH_COLUMN, 0 );

// Version 1 DOES NOT have filtering
$sql_filter = '';

// Get filtered count
$stmt = $pdo->prepare( 'SELECT COUNT(*) FROM challenges WHERE challenge_id IN ( SELECT fk_challenge_id FROM challenge_participant ' . $sql_filter . ')' );
$stmt->execute();
$filterCount = $stmt->fetch( PDO::FETCH_COLUMN, 0 );

// Ordering
$sql_order = ' ORDER BY fk_challenge_id ASC ';

// Paging support
// Turn these into bind variables
$sql_limit = ' LIMIT '.intval( $start ).', '.  intval( $length ) . ' ';

//Start Date  End Date  Start Weight  Goal Weight Rank  Team Size
//  SELECT challenge_id, fk_created_by, challenge_name, start_date, end_date, challenge_type
$sql_string = '
  SELECT start_date, end_date, 260, 250, 1, 5
    FROM challenges
   WHERE challenge_id IN (SELECT fk_challenge_id FROM challenge_participant WHERE fk_user_id = 1)
ORDER BY start_date asc';


//$sql_string = '
//  SELECT mtu_id
//        ,date_format( date, "%Y/%m/%d %H:%i" )
//        ,watts
//        ,volts
//    FROM elec__elec_usage' . $sql_filter . $sql_order . $sql_limit;
//

$stmt = $pdo->prepare( $sql_string );
$stmt->execute();

$allData = $stmt->fetchAll( PDO::FETCH_NUM );

echo '{';
echo '"draw": ' . $draw;
echo ',"recordsTotal": ' . $totalCount;
echo ',"recordsFiltered": ' . $filterCount;
echo ',"data": ';
echo json_encode( $allData );
echo '}';

return;




/*
echo '{
   "history":{
     "0": {
       "start_date": "2016-01-01"
      ,"end_date": "2016-01-31"
      ,"start_weight": 245
      ,"goal_weight": 230
      ,"end_weight": 235
      ,"rank": 3
      ,"rank_of": 7
    }
    ,"1": {
       "start_date": "2016-02-01"
      ,"end_date": "2016-02-27"
      ,"start_weight": 235
      ,"goal_weight":  220
      ,"end_weight": 220
      ,"rank": 1
      ,"rank_of": 5
    }
  }
  ,"history_count": 2
}';
*/

/*
function createOneRec( $start_date, $end_date, $start_weight, $goal_weight, $end_weight, $rank, $rank_of ){
  $challengeRec = array(
       "start_date" => $start_date
      ,"end_date" => $end_date
      ,"start_weight" => $start_weight
      ,"goal_weight" => $goal_weight
      ,"end_weight" => $end_weight
      ,"rank" =>  $rank
      ,"rank_of" => $rank_of
    );
  return $challengeRec;
}

// Using "d0" instead of just "0" because using "0" (numeric only) leads to creation of a json array instead of named elements
$history = array(
   "d0" => createOneRec( "2016-01-01", "2016-01-31", 245, 230, 235, 3, 7 )
  ,"d1" => createOneRec( "2016-02-01", "2016-02-28", 235, 220, 220, 1, 5 )
);

$data = array( "history" => $history, "history_count" => count( $history ) );
echo json_encode( $data );
*/
?>