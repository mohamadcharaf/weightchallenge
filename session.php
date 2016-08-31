<?php

/**
  * Starts a session with a specific timeout and a specific GC probability.
  * @param int $timeout The number of seconds until it should time out.
  *        default to seven days
  * @param int $probability The probablity, in int percentage, that the garbage
  *        collection routine will be triggered right now.
  *        default to 100%
  * @param strint $cookie_domain The domain path for the cookie.
  *        default to root
  */
function session_start_timeout( $timeout = (60 * 60 * 24 * 7), $probability = 100, $cookie_domain = '/' ){
  ini_set( 'session.gc_maxlifetime', $timeout );      // Set the max lifetime
  ini_set( 'session.cookie_lifetime', $timeout );     // Set the session cookie to timout
  ini_set( 'session.gc_probability', $probability );  // Set the chance to trigger the garbage collection.
  ini_set( 'session.gc_divisor', 100 );               // Should always be 100

  session_start();

  /**
    * Renew the time left until this session times out.  If you skip this, the session will time out based
    * on the time when it was created, rather than when it was last used.
    */
  if( isset( $_COOKIE[ session_name() ] ) ){
    setcookie( session_name(), $_COOKIE[ session_name() ], time() + $timeout, $cookie_domain );
  }
}

session_start_timeout();

require_once( 'user.php' );
$session = new USER();

if( !$session->is_loggedin() ){
  // session no set redirects to login page
  $session->redirect( 'index.php' );
}
?>