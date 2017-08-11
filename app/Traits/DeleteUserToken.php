<?php

namespace App\Traits;

trait DeleteUserToken {

  /**
    * deletes token from given user (sets value to NULL)
    *
    * @param $user: a user
    * @return void
    */
  public function deleteUserToken($user)
  {
    $user->token = NULL;
    $user->update();
  }
}
