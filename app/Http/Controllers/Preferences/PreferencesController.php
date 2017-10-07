<?php

namespace App\Http\Controllers\Preferences;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

class PreferencesController extends Controller
{
  /**
    * Middlewares:
    *  - auth
    *
    */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
    * view account preferences
    *
    * @return view
    */
  public function showPreferences(Request $request)
  {
    $view = $request->filled('view') ? $request->input('view') : 'account';

    $data = ['view' => $view];

    if($view == 'app') {

      $appPreferences = Auth::user()->preferences;

      $data['xp_goal'] = $appPreferences->xp_goal;
      $data['setting'] = [
        'assignment'  => $appPreferences->show_assignment,
        'divider'     => $appPreferences->show_divider,
        'keyboard'    => $appPreferences->show_keyboard,
      ];
      $data['keyboardLayout'] = $appPreferences->keyboard;
    }

    return view('preferences.index', $data);
  }
}
