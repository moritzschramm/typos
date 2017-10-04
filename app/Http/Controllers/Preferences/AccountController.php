<?php

namespace App\Http\Controllers\Preferences;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Traits\CreateUserToken;

use App\Mail\VerifyAccountMail;

use Auth, Validator, Mail;

class AccountController extends Controller
{
  use CreateUserToken;
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
    * changes email of user
    * NOTE: this will log user out
    *
    * @param Request
    * @return redirect
    */
  public function changeEmail(Request $request)
  {
    // validate email and password
    $validator = Validator::make($request->all(), [
      'email'     => 'required|email|max:'  . config('database.stringLength'),
      'password'  => 'required|max:'        . config('database.stringLength'),
    ], [
      'required'  => 'errors.required',
      'email'     => 'errors.email',
      'max'       => 'errors.max',
    ]);

    $user = Auth::user();

    // check if password is correct
    $validator->after(function ($validator) use ($request, $user) {

      if( ! Auth::validate([
        'username' => $user->username,
        'password' => $request->input('password')]) ) {

        $validator->errors()->add('password', 'errors.wrongPassword');
      }
    });

    if($validator->fails()) {

      return back()->withInput()->withErrors($validator, 'email');

    } else {

      // change email
      $user->email = $request->input('email');
      $user->verified = NULL;
      $user->update();

      // create new token (for user to verify new email address)
      $token = $this->createUserToken($user);

      $verifyUrl = url('/verify/' . $user->uuid . '/' . $token);

      Mail::to($user->email)->send(new VerifyAccountMail($verifyUrl));

      // log user out
      Auth::logout();

      return redirect('/')->with('notification-success', 'preferences.emailChanged');
    }
  }

  /**
    * deletes user's statistics
    *
    * @param Request
    * @return redirect (back)
    */
  public function deleteStats(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'password' => 'required',
    ],[
      'required' => 'errors.required',
    ]);

    $user = Auth::user();

    $validator->after(function ($validator) use ($request, $user) {

      if( ! Auth::validate([
        'username'  => $user->username,
        'password'  => $request->input('password')]) ) {

        $validator->errors()->add('password', 'errors.wrongPassword');
      }
    });

    if($validator->fails()) {

      session()->flash('triggerModalStats', true);
      return back()->withErrors($validator, 'stats');

    } else {

      $user->lectionResults()->delete();

      return back()->with('notification-success', 'preferences.statsDeleted');
    }
  }

  /**
    * deletes user account after logging user out
    *
    * @param Request
    * @return redirect
    */
  public function deleteAccount(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'password' => 'required',
    ],[
      'required' => 'errors.required',
    ]);

    $user = Auth::user();

    $validator->after(function ($validator) use ($request, $user) {

      if( ! Auth::validate([
        'username'  => $user->username,
        'password'  => $request->input('password')]) ) {

        $validator->errors()->add('password', 'errors.wrongPassword');
      }
    });

    if($validator->fails()) {

      session()->flash('triggerModalAccount', true);
      return back()->withErrors($validator, 'account');

    } else {

      Auth::logout();
      session()->flush();

      $user->deleteAccount();

      return redirect('/')->with('notification-success', 'preferences.accountDeleted');
    }
  }
}
