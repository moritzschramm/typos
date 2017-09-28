<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\Lection;
use App\Models\Exercise;

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

  /**
    * show dashboard index view
    *
    * @return view
    */
  public function showDashboard()
  {
    $locale = Auth::user()->preferences->getKeyboardLocale();

    $lections   = Lection::where('locale', $locale)->get();
    $exercises  = Exercise::where('id_user', Auth::user()->id_user)->get();

    return view('dashboard.index', [
      'lections'  => $lections,
      'exercises' => $exercises,
    ]);
  }
}
