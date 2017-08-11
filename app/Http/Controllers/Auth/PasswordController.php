<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
  public function __construct()
  {
    $this->middleware('throttle:5,10')->only('requestPasswordReset');
  }

  public function showForgotPassword()
  {
    return view('auth.forgotPassword');
  }

  public function showResetPassword()
  {
    return view('auth.resetPassword');
  }

  public function requestPasswordReset(Request $request)
  {

  }

  public function resetPassword(Request $request)
  {

  }
}
