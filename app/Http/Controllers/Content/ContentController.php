<?php

namespace App\Http\Controllers\Content;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
    return view('public.help');
  }
}
