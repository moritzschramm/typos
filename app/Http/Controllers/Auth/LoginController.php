<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth, Validator;
use App\Models\User;

class LoginController extends Controller
{
  public static $redirectTo = '/dashboard';

  /**
    * Middlewares:
    * - guest (except logout(), login())
    * - throttle (only login())
    */
  public function __construct()
  {
    $this->middleware('guest')->except('logout', 'login');
    $this->middleware('throttle:5,2')->only('login');
  }

  /**
    * show login form
    *
    * @return view
    */
  public function showLogin()
  {
    return view('auth.login');
  }

  /**
    * validates and checks user email and password,
    * logs a user in if creds are correct and user is verified
    *
    * TEST: Feature/LoginTest
    *
    * @return redirect
    */
  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'emailOrUsername'     => 'required|max:'  . config('database.stringLength'),
      'password'  => 'required|max:'  . config('database.stringLength'),
    ], [
      'required'  => 'errors.required',
      'max'       => 'errors.max',
    ]);

    $validator->after(function ($validator) use ($request) {

      $emailOrUsername  = $request->input('emailOrUsername');
      $password         = $request->input('password');
      $with_remember_me = $request->input('remember_me') == 'on';
      $user = User::where('email', $emailOrUsername)
                    ->orWhere('username', $emailOrUsername)
                    ->first();

      if($user) {

        if( ! is_null($user->verified)) { # has user verified his email?

          if( Auth::validate(['email'     => $emailOrUsername, 'password' => $password]) ||
              Auth::validate(['username'  => $emailOrUsername, 'password' => $password]) ) {

            session()->regenerate();  # prevent session fixation attacks

            Auth::login($user, $with_remember_me);
            return;

          } # else: wrong creds

        } else {  # unverified user

          $validator->errors()->add('unverified', 'errors.unverified');
          return;
        }

      } # else: wrong creds

      $validator->errors()->add('credentials', 'errors.credentials');

    });

    if($validator->fails()) {

      return back()->withInput()->withErrors($validator);

    } else {

      return redirect(self::$redirectTo); #->intended(self::$redirectTo);
    }
  }

  /**
    * logout user
    *
    * TEST: Feature/LogoutTest
    *
    * @return redirect
    */
  public function logout()
  {
    $locale = session('app_locale');

    Auth::logout();
    session()->flush();

    session(['app_locale' => $locale]);

    return redirect('/')->with('notification-success', 'auth.loggedOut');
  }
}
