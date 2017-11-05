<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User, App\Models\UserPreference;
use App\Traits\CreateUserToken, App\Traits\PasswordCheck, App\Traits\ProfanityFilter;
use Auth, Mail, Validator;
use App\Mail\VerifyAccountMail;

class RegisterController extends Controller
{
  use CreateUserToken, PasswordCheck, ProfanityFilter;

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
    * registers a user after validating his email (optional) and password
    * NOTE: user will be unverified (if user has email) after
    *       registration (needs to confirm email first)
    *
    * @return redirect
    */
  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'username'  => 'required|alpha_dash|unique:users,username|max:'  . config('database.stringLength'),
      'email'     => 'nullable|email|unique:users,email|max:'          . config('database.stringLength'),
      'password'  => 'required|max:'                                   . config('database.stringLength'),
      'confirm'   => 'required|max:'                                   . config('database.stringLength'),
      'keyboard'  => 'required',
      'checkbox'  => 'required',
    ], [
      'required'          => 'errors.required',
      'email'             => 'errors.email',
      'max'               => 'errors.max',
      'email.unique'      => 'errors.uniqueEmail',
      'username.unique'   => 'errors.uniqueUsername',
      'checkbox.required' => 'errors.acceptPolicy',
      'alpha_dash'        => 'errors.alpha_dash',
    ]);

    $validator->after(function ($validator) use ($request) {

      # reCaptcha validation
      if(env('APP_ENV') !== 'testing') {

        $recaptcha = new \ReCaptcha\ReCaptcha(config('services.recaptcha.private'));
        $response  = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());

        if ( ! $response->isSuccess()) {

          $validator->errors()->add('captcha', 'errors.captchaError');
          return;
        }
      }

      if($this->isProfane($request->input('username'))) {

        $validator->errors()->add('username', 'errors.profanity');
        return;
      }

      $password = $request->input('password');
      $confirm  = $request->input('confirm');

      if($password !== $confirm) {

        $validator->errors()->add('confirm', 'errors.differs');

      } else if($this->is_weak_password($password)) {

        $validator->errors()->add('password', 'errors.weak_password');

      } else if( ! in_array($request->input('keyboard'), config('app.available_keyboards'))) {

        $validator->errors()->add('keyboard', 'preferences.keyboardUnavailable');
      }
    });



    if($validator->fails()) {

      return back()->withInput()->withErrors($validator);

    } else {

      $hasEmail = $request->filled('email');

      $user           = new User;
      $user->username = $request->input('username');
      $user->email    = $request->input('email');
      $user->uuid     = User::uuid();
      $user->password = bcrypt($request->input('password'));
      $user->verified = $hasEmail ? NULL : date('Y-m-d H:i:s');
      $user->locale   = session('app_locale');
      $user->save();

      $preferences = new UserPreference(UserPreference::defaults($request->input('keyboard')));
      $preferences->id_user = $user->id_user;
      $preferences->save();

      if($hasEmail) {

        $token = $this->createUserToken($user);

        $verifyUrl = url('/verify/' . $user->uuid . '/' . $token);

        Mail::to($user->email)->send(new VerifyAccountMail($verifyUrl));

        return view('auth.registerSuccess', ['email' => $user->email]);

      } else {

        Auth::login($user);

        return redirect('/dashboard')
                ->with('notification-success', 'auth.register.success.title');
      }
    }
  }
}
