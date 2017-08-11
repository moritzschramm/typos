<?php

namespace App\Traits;

trait PasswordCheck {

  /**
    * TODO
    * checks if a password is too weak
    * criterea:
    * - min 8 characters
    * - not in weak password list
    *
    * @param $password
    * @return boolean: if password is to weak to accept
    */
  public function is_weak_password($password)
  {
    if(strlen($password) < 8) {

      return true;
    }
    #TODO

    return false;
  }
}
