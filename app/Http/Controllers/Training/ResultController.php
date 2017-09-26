<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

use App\Models\LectionResult;

use App\Traits\CalculateXP, App\Traits\ValidateLectionNonce;

class ResultController extends Controller
{
  use CalculateXP, ValidateLectionNonce;
  /**
    * Middlewares:
    *  - auth
    *
    */
  public function __construct()
  {
    $this->middleware('auth')->except('show');
  }

  /**
    * show result of last lection/training/exercise
    * (results from session)
    *
    * @return view
    */
  public function show()
  {
    // in case page gets reloaded, reflash the session storage
    session()->reflash();

    return response()->view('training.results', [
      'xp'            => session('xp'),
      'velocity'      => session('velocity'),
      'error_amount'  => session('error_amount'),
      'keystrokes'    => session('keystrokes'),
      'cheated'       => session('cheated'),
      'xp_goal'       => Auth::check() ? Auth::user()->preferences->xp_goal : 30,
    ],
    session('cheated') ? 418 : 200);
  }

  /**
    * stores results of training/lection/exercise after validating input
    *
    * @param Request $request
    * @return Response (200)
    */
  public function upload(Request $request)
  {
    $user       = Auth::user();
    $nonce      = session('nonce');
    $velocity   = $request->input('velocity');
    $errors     = $request->input('errors');
    $keystrokes = $request->input('keystrokes');
    $xp         = $this->calculateXP($nonce);

    // check results and, if valid, save them
    if($this->validateLectionNonce($nonce, $velocity)) {

      $result = new LectionResult([
        'id_user'     => $user->id_user,
        'velocity'    => $velocity,
        'errors'      => $errors,
        'keystrokes'  => $keystrokes,
        'xp'          => $xp,
      ]);

      $result->save();

    } else {

      session()->flash('cheated', true);
    }

    // flash results to session (so that they can be used in show())
    session()->flash('velocity',      $velocity);
    session()->flash('error_amount',  $errors);
    session()->flash('keystrokes',    $keystrokes);
    session()->flash('xp', LectionResult::getTodaysXP($user->id_user));

    // nonce has been validated and isn't needed anymore
    session()->forget('nonce');

    return response('', 200);
  }
}
