<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use App\Models\User;
use App\Traits\CreateUserToken, App\Traits\ValidateUserToken, App\Traits\DeleteUserToken, App\Traits\PasswordCheck;
use Mail;
use App\Mail\PasswordResetMail;

class PasswordController extends Controller
{
  use CreateUserToken, ValidateUserToken, DeleteUserToken, PasswordCheck;

  /**
    * Middleware:
    * - throttle (only requestPasswordReset())
    */
  public function __construct()
  {
    $this->middleware('throttle:5,10')->only('requestPasswordReset');
  }

  /**
    * shows forgot password form
    *
    * @return view
    */
  public function showForgotPassword()
  {
    return view('auth.forgotPassword');
  }

  /**
    * shows reset password form
    *
    * @return view
    */
  public function showResetPassword(Request $request)
  {
    session()->reflash();
    $uuid = $request->input('uid');
    $token = $request->input('token');

    if(is_null($this->validateUserToken($uuid, $token))) {

      abort(403);
    }

    session()->flash('uuid', $uuid);
    session()->flash('token', $token);

    return view('auth.resetPassword');
  }

  /**
    * creates a token for the user and emails a password reset link
    *
    * @return redirect
    */
  public function requestPasswordReset(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email|exists:users,email|max:' . config('database.stringLength'),
    ], [
      'required'  => 'errors.required',
      'email'     => 'errors.email',
      'exists'    => 'errors.exists',
      'max'       => 'errors.max',
    ]);

    if($validator->fails()) {

      return back()->withInput()->withErrors($validator);

    } else {

      $user = User::where('email', $request->input('email'))->first();
      $token = $this->createUserToken($user);
      $resetUrl = url('/password/reset?uid=' . $user->uuid . '&token=' . $token);

      Mail::to($user->email)->send(new PasswordResetMail($resetUrl));

      return redirect('/login')->with('notification', 'notifications.password.reset.requested');
    }
  }

  /**
    * resets the password of the user
    *
    * @return redirect
    */
  public function resetPassword(Request $request)
  {
    session()->reflash();

    $validator = Validator::make($request->all(), [
      'password'  => 'required|min:8|max:' . config('database.stringLength'),
      'confirm'   => 'required',
    ], [
      'required'  => 'errors.required',
      'min'       => 'errors.min',
      'max'       => 'errors.max',
    ]);

    $validator->after(function ($validator) use ($request) {

      if($request->input('password') !== $request->input('confirm')) {

        $validator->errors()->add('confirm', 'errors.differs');

      } else if($this->is_weak_password($request->input('password'))) {

        $validator->errors()->add('password', 'errors.weak_password');
      }
    });

    if($validator->fails()) {

      return back()->withErrors($validator);

    } else {

      $uuid = session('uuid');
      $token = session('token');

      $user = User::where('uuid', $uuid)->first();
      $user->password = bcrypt($request->input('password'));
      $user->update();

      $this->deleteUserToken($user);

      session()->flush(); # delete old error messages (to prevent confusion)

      return redirect('/login')->with('notification-success', 'notifications.password.reset.success');
    }
  }
}
