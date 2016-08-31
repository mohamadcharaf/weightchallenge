<?php
require_once( 'dbconfig.php' );

class USER{
  private $conn;
  public  $uname;

  public function __construct( $uname = null, $session = null ){
//echo '<script>console.log( "user constructor" )</script>';
    $database = new Database();
    $db = $database->dbConnection();
    $this->conn = $db;

    if( $uname == null && $session == null && $this->is_loggedin() ){
//echo '<script>console.log( "user constructor condition 1" )</script>';
//echo '<script>console.log( "user constructor user name ' . $_SESSION[ 'user_name' ] . '" )</script>';
      $this->uname = $_SESSION[ 'user_name' ];
      $this->session = $_SESSION[ 'user_session' ];
    }
    else if( $uname != null && $session != null && $this->hasSession( $uname, $session ) ){
//echo '<script>console.log( "user constructor condition 2" )</script>';
      $this->uname = $uname;
      $this->session = $session;
    }
//else{
//echo '<script>console.log( "user constructor fall through" )</script>';
//}

  }

  public function runQuery( $sql ){
    $stmt = $this->conn->prepare($sql);
    return $stmt;
  }

  public function register( $uname, $umail, $upass ){
    try{
      $new_password = password_hash( $upass, PASSWORD_DEFAULT );

      $stmt = $this->conn->prepare( "INSERT INTO users(user_name,user_email,user_pass) VALUES(:uname, :umail, :upass)" );

      $stmt->bindparam( ":uname", $uname );
      $stmt->bindparam( ":umail", $umail );
      $stmt->bindparam( ":upass", $new_password);

      $stmt->execute();

      return $stmt;
    }
    catch( PDOException $e ){
      echo $e->getMessage();
    }
  }


  public function doLogin( $uname, $umail, $upass ){
    try{
      $stmt = $this->conn->prepare( "SELECT user_id, user_name, user_email, user_pass FROM users WHERE user_name=:uname OR user_email=:umail " );
      $stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
      $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
      if( $stmt->rowCount() == 1 ){
        if( password_verify( $upass, $userRow[ 'user_pass' ] ) ){
            $this->uname = $uname;
            $_SESSION[ 'user_session' ] = $userRow['user_id'];
            $_SESSION[ 'user_name' ] = $uname;
            return true;
        }
      }
    }
    catch( PDOException $e ){
      echo $e->getMessage();
    }
    return false;
  }

  public function is_loggedin(){
    if( isset( $_SESSION['user_session'] ) ){
      return true;
    }
  }


  // For any given user name and session ID this tests for validity. (Can be used in back end calls too)
  public function hasSession( $uname, $user_session ){
echo '<script>console.log( "Checking hasSession()" )</script>';
    try{
      $stmt = $this->conn->prepare( "SELECT count(*) FROM users WHERE sessionid = ? AND user_name = ?" );
      $stmt->bind_param( 'ss', $user_session, $uname );
      $stmt->execute();

      if( $stmt->fetchColumn() == 1 ){
        // one row, one column in results.  Value should be 0 or 1.
        return true;
      }
    }
    catch( Exception $e ){
      // The way the SQL is written this really ought not to happen.
      // Do nothing, but fall through to false.
    }
    return false;
  }


  public function redirect( $url ){
    header( "Location: $url" );
  }


  public function doLogout(){
    session_destroy();
    unset( $_SESSION['user_session'] );
    return true;
  }
}
?>