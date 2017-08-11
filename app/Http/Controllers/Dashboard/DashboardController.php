<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
  /**
    * Middlewares:
    * - auth
    */
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function showDashboard()
  {
    return view('dashboard.index');
  }
}
