<?php

namespace App\Http\Controllers\Preferences;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth, Validator;

class AppPreferencesController extends Controller
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
    * update user's app preferences
    *
    * @param Request
    * @return redirect (back)
    */
  public function editAppPreferences(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'xp_goal'         => 'required|integer|max:500',
      'keyboard_layout' => 'required',
    ], [
      'required'      => 'errors.required',
      'integer'       => 'errors.integer',
      'xp_goal.max'   => 'errors.numericMax',
    ]);

    $validator->after(function ($validator) use ($request) {

      if( ! in_array($request->input('keyboard_layout'), config('app.available_keyboards'))) {

        $validator->errors()->add('keyboard_layout', 'preferences.keyboardUnavailable');
      }
    });

    if($validator->fails()) {

      return back()->withInput()->withErrors($validator);

    } else {

      $preferences = Auth::user()->preferences;

      $preferences->xp_goal         = $request->input('xp_goal');
      $preferences->show_assignment = $request->has('setting_assignment');
      $preferences->show_divider    = $request->has('setting_divider');
      $preferences->show_keyboard   = $request->has('setting_keyboard');
      $preferences->keyboard        = $request->input('keyboard_layout');
      $preferences->update();

      return back()->with('notification-success', 'preferences.saved');
    }
  }
}
