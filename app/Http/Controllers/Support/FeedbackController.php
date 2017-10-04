<?php

namespace App\Http\Controllers\Support;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator, Auth;

use App\Mail\SupportMail;

class FeedbackController extends Controller
{
  /**
    * Middlewares:
    *  - throttle (max 6 request, 10 min cooldown)
    *
    */
  public function __construct()
  {
    $this->middleware('throttle:6,10')->only('sendFeedback');
  }

  /**
    * validates request from feedback form and sends email to support team
    *
    * @param Request
    * @return JSON
    */
  public function sendFeedback(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email'   => 'required|email',
      'message' => 'required',
    ], [
      'required'  => 'errors.required',
      'email'     => 'errors.email',
    ]);

    if($validator->fails()) {

      return [
        'status' => 'error',
        'validator' => $validator->messages(),
      ];

    } else {

      $email = $request->input('email');
      $message = $request->input('message');
      $userId = Auth::check() ? Auth::user()->uuid : -1;

      Mail::to(config('mail.from.support'))
            ->send(new SupportMail('Feedback', $email, $message, $userId));

      return [
        'status' => 'success',
      ];
    }
  }
}
