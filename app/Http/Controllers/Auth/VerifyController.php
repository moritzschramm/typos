<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Traits\ValidateUserToken;
use App\Traits\DeleteUserToken;

class VerifyController extends Controller
{
  use ValidateUserToken, DeleteUserToken;

  /**
    * Middlewares:
    * - throttle
    */
  public function __construct()
  {
    $this->middleware('throttle:5,10');
  }

  public function verifyUser(Request $request, $uuid, $token)
  {
    if($user = $this->validateUserToken($uuid, $token)) {

      // delete token
      $this->deleteUserToken($user);

      // verify user
      $user->verified = 1;
      $user->update();

      return redirect('/login')->with('notification-success', 'verified');

    } else {

      abort(403);
    }
  }
}
