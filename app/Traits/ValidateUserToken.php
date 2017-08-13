<?php

namespace App\Traits;

use App\Models\User;

trait ValidateUserToken {

  /**
    * validates a token
    *
    * @param $uuid: the unique id of a user
    * @param $token: a token
    * @return User|null: returns a User object if token is valid, null otherwise
    */
  public function validateUserToken($uuid, $token)
  {
    $user = User::where('uuid', $uuid)->first();

    if($user) {

      if($user->token === $token) return $user;
    }

    return null;
  }
}
