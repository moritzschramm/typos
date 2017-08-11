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
    $this->middleware('guest');
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
}
