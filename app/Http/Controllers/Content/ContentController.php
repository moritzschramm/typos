<?php

namespace App\Http\Controllers\Content;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

class ContentController extends Controller
{
  /**
    * Middlewares:
    * - guest
    */
  public function __construct()
  {
    $this->middleware('guest')->only('index');
  }

  /**
    * return index view
    *
    * @return view
    */
  public function index()
  {
    return view('public.index');
  }

  /**
    * return privacy policy
    *
    * @return view
    */
  public function privacy()
  {
    return view('public.privacy');
  }

  /**
    * return legal notice
    *
    * @return view
    */
  public function notice()
  {
    return view('public.notice');
  }

  /**
    * return help view
    *
    * @return view
    */
  public function help()
  {
    if(Auth::check()) {

      $keyboardLayout = Auth::user()->preferences->keyboard;

    } else {

      switch(session('app_locale')) {

        case 'de':  $keyboardLayout = 'de-de'; break;
        default:    $keyboardLayout = 'en-us'; break;
      }
    }

    return view('public.help', ['keyboardLayout' => $keyboardLayout]);
  }
}
