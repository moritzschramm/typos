<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\Lection;
use App\Models\Exercise;
use App\Models\LectionResult;

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
  public function showDashboard(Request $request)
  {
    $view = $request->filled('view') ? $request->input('view') : 'lections';

    $lections     = [];
    $exercises    = [];
    $preferences  = Auth::user()->preferences;

    switch($view) {

      case 'lections':
        $locale = $preferences->getKeyboardLocale();
        $lections = Lection::where('locale', $locale)->get();
        break;

      case 'exercises':
        $exercises = Exercise::where('id_user', Auth::user()->id_user)->get();
        break;

      default: abort(404);
    }

    $xp       = LectionResult::getTodaysXP(Auth::user()->id_user);
    $xp_goal  = $preferences->xp_goal;

    return view('dashboard.index', [
      'view'      => $view,
      'lections'  => $lections,
      'exercises' => $exercises,
      'xp'        => $xp,
      'xp_goal'   => $xp_goal,
    ]);
  }
}
