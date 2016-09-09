<?php
require_once( 'dbconfig.php' );

class USER{
  private $conn;
  public  $uname;
  public  $uid;

  public function __construct( $uname = null, $session = null ){
    $database = new Database();
    $this->conn = $database->dbConnection();

    if( $uname == null && $session == null && $this->is_loggedin() ){
      $this->uname = $_SESSION[ 'user_name' ];
      $this->session = $_SESSION[ 'user_session' ];
//      $this->hasSession( $this->uname, $this->session );  // go get the user_id
//QQQ work around
$this->uid = $this->session;
    }
    else if( $uname != null && $session != null && $this->hasSession( $uname, $session ) ){
      $this->uname = $uname;
      $this->session = $session;
    }
  }

/* this is not needed and even if it was, should be in the database class.
  public function runQuery( $sql ){
    $stmt = $this->conn->prepare( $sql );
    return $stmt;
  }
*/

  public function register( $uname, $umail, $upass ){
    try{
      $new_password = password_hash( $upass, PASSWORD_DEFAULT );

      $stmt = $this->conn->prepare( "INSERT INTO users(user_name,user_email,user_pass) VALUES(:uname, :umail, :upass)" );

      $stmt->bindParam( ":uname", $uname );
      $stmt->bindParam( ":umail", $umail );
      $stmt->bindParam( ":upass", $new_password);

      $stmt->execute();

      return $stmt;
    }
    catch( PDOException $e ){
      echo $e->getMessage();
    }
  }


  public function doLogin( $uname, $umail, $upass ){
    try{
      $stmt = $this->conn->prepare( "SELECT user_id, user_name, user_email, user_pass FROM users WHERE user_name = :uname OR user_email = :umail" );

      $stmt->bindParam( ':uname', $uname );
      $stmt->bindParam( ':umail', $umail );
      $stmt->execute();

      $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
      if( $stmt->rowCount() == 1 ){
        if( password_verify( $upass, $userRow[ 'user_pass' ] ) ){
            $this->uname = $uname;
            $user_session = substr( MD5( microtime() ), 0, 128);
            $_SESSION[ 'user_session' ] = $user_session;
            $_SESSION[ 'user_name' ] = $uname;

            $stmt = $this->conn->prepare( "UPDATE users SET sessionid = :user_session WHERE user_name = :uname" );
            $stmt->bindParam( ':user_session', $user_session );
            $stmt->bindParam( ':uname', $uname );
            $stmt->execute();

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
//QQQ requires session_id in the user table!!
  public function hasSession( $uname, $user_session ){
    try{
      $stmt = $this->conn->prepare( "SELECT count(*) FROM users WHERE sessionid = :user_session AND user_name = :uname" );
      $stmt->bindParam( ":user_session", $user_session );
      $stmt->bindParam( ":uname", $uname );
      $stmt->execute();

      if( $stmt->fetchColumn() == 1 ){
        $stmt2 = $this->conn->prepare( "SELECT user_id FROM users WHERE sessionid = ? AND user_name = ?" );
        $stmt2->bind_param( 'ss', $user_session, $uname );
        $stmt2->execute();
        $this->uid = $stmt2->fetchColumn( 0 );
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