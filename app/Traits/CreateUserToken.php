<?php

namespace App\Traits;

trait CreateUserToken {

  /**
    * create a user token in the database
    *
    * @param $user: a user
    * @param $length (optional): the length of the token
    * @return string: the generated token
    */
  public function createUserToken($user, $length = 32)
  {
    $token = generateSecureString($length);

    $user->token = $token;
    $user->update();

    return $token;
  }
}
