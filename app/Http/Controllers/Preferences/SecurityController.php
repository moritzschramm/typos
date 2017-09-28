<?php

namespace App\Http\Controllers\Preferences;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Traits\PasswordCheck;

use Validator, Auth;

class SecurityController extends Controller
{
  use PasswordCheck;
  /**
    * Middlewares:
    *  - auth
    *
    */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
    * change password of user
    *
    * @param Request
    * @return redirect (back)
    */
  public function changePassword(Request $request)
  {
    // validate input
    $validator = Validator::make($request->all(), [
      'currentPassword'   => 'required|max:' . config('database.stringLength'),
      'newPassword'       => 'required|max:' . config('database.stringLength'),
      'confirm'           => 'required|max:' . config('database.stringLength'),
    ], [
      'required'  => 'errors.required',
      'max'       => 'errors.max',
    ]);

    $user = Auth::user();

    // additional validation rules
    $validator->after(function ($validator) use ($request, $user) {

      $currentPassword  = $request->input('currentPassword');
      $newPassword      = $request->input('newPassword');
      $confirm          = $request->input('confirm');

      if( ! Auth::validate([
        'email' => $user->email,
        'password' => $currentPassword])) {

        $validator->errors()->add('credentials', 'errors.credentials');

      } else if($newPassword !== $confirm) {

        $validator->errors()->add('confirm', 'errors.differs');

      } else if($this->is_weak_password($newPassword)) {

        $validator->errors()->add('password', 'errors.weak_password');

      }
    });

    if($validator->fails()) {

      return back()->withInput()->withErrors($validator);

    } else {

      // change user's password
      $user->password = bcrypt($request->input('newPassword'));
      $user->update();

      return back()->with('notification-success', 'preferences.passwordChanged');
    }
  }
}
