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
    $this->middleware('throttle:5,1')->only('login');
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
      'email'     => 'required|email|max:'  . config('database.stringLength'),
      'password'  => 'required|max:'        . config('database.stringLength'),
    ], [
      'required'  => 'req',
      'email'     => 'email',
      'max'       => 'max',
    ]);

    $validator->after(function ($validator) use ($request) {

      $email            = $request->input('email');
      $password         = $request->input('password');
      $with_remember_me = $request->input('remember_me') == 'on';
      $user = User::where('email', $email)->first();

      if($user->verified) { # has user verified his email?

        if(Auth::validate(['email' => $email, 'password' => $password])) {  # check email and password

          session()->regenerate();  # prevent session fixation attacks

          Auth::login($user, $with_remember_me);

        } else {  # wrong creds

          $validator->errors()->add('credentials', 'wrong creds'); # TODO: use translation keys instead of error txt
        }

      } else {  # unverified user

        $validator->errors()->add('unverified', 'unverified'); # TODO use translation keys
      }
    });

    if($validator->fails()) {

      return back()->withErrors($validator);

    } else {

      return redirect()->intended(self::$redirectTo);
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
    Auth::logout();
    session()->flush();

    return redirect('/');
  }
}
