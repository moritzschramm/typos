<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Traits\CreateUserToken, App\Traits\PasswordCheck;
use Validator, Mail;
use App\Mail\VerifyAccountMail;

class RegisterController extends Controller
{
  use CreateUserToken, PasswordCheck;

  /**
    * Middlewares:
    * - guest
    */
  public function __construct()
  {
    $this->middleware('guest');
  }

  /**
    * shows registration form
    *
    * @return view
    */
  public function showRegister()
  {
    return view('auth.register');
  }

  /**
    * shows register success view
    *
    * @return view
    */
  public function showSuccess()
  {
    return view('auth.registerSuccess');
  }

  /**
    * registers a user after validating his email and password
    * NOTE: user will be unverified after registration (needs to confirm email first)
    *
    * @return redirect
    */
  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email'     => 'required|email|unique:users,email|max:' . config('database.stringLength'),
      'password'  => 'required|max:'       . config('database.stringLength'),
      'confirm'   => 'required|max:'       . config('database.stringLength'),
    ], [
      'required' => 'req',
      'email' => 'em',
      'max' => 'max',
    ]);

    $validator->after(function ($validator) use ($request) {

      $password = $request->input('password');
      $confirm  = $request->input('confirm');

      if($password !== $confirm) {

        $validator->errors()->add('confirm', 'password dont match');

      } else if($this->is_weak_password($password)) {

        $validator->errors()->add('password', 'password too weak');
      }
    });

    if($validator->fails()) {

      return back()->withErrors($validator);

    } else {

      $user = new User;
      $user->email = $request->input('email');
      $user->uuid = uuidv4();
      $user->password = bcrypt($request->input('password'));
      $user->verified = 0;
      $user->save();

      $token = $this->createUserToken($user);

      $verifyUrl = url('/user/verify/' . $user->uuid . '/' . $token);

      Mail::to($user->email)->send(new VerifyAccountMail($verifyUrl));

      return redirect('/register/success');
    }
  }
}
