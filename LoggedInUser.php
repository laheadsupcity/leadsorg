<?php
require_once('config.php');

class LoggedInUser {

  /*
    This is really dumb, but they don't yet want to add a field on the user table
    and have requested that I hardcode this for now
  */
  public static function isAdminUser($user_id) {
    return (int) $user_id == 5;
  }

}
