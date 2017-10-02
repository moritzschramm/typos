<?php

namespace App\Http\Controllers\Support;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator, Auth, Mail;
use App\Mail\SupportMail;

class SupportController extends Controller
{
  /**
    * Middlewares:
    *  - throttle (max 6 request, 10 min cooldown)
    *
    */
  public function __construct()
  {
    $this->middleware('throttle:6,10')->only('sendSupportRequest');
  }

  /**
    * show support form
    *
    * @return view
    */
  public function showSupport()
  {
    return view('public.support');
  }

  /**
    * validate input from support form and send mail to support team
    *
    * @param Request
    * @return
    */
  public function sendSupportRequest(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email'   => 'required|email',
      'message' => 'required',
    ], [
      'required'  => 'errors.required',
      'email'     => 'errors.email',
    ]);

    if($validator->fails()) {

      return back()->withInput()->withErrors($validator);

    } else {


      $email = $request->input('email');
      $message = $request->input('message');
      $userId = Auth::check() ? Auth::user()->id_user : -1;

      Mail::to(config('mail.from.support'))
            ->send(new SupportMail('Support request', $email, $message, $userId));

      return redirect('/support')->with('notification-success', 'mail.support.sent');
    }
  }
}
