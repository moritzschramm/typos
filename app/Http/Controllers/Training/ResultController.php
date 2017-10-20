<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

use App\Models\LectionResult;
use App\Models\LectionNonce;

use App\Traits\CalculateXP;


class ResultController extends Controller
{
  use CalculateXP;
  /**
    * Middlewares:
    *  - auth (except show())
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

    if( ! session()->has('xp', 'velocity', 'error_amount', 'keystrokes')) {

      return abort(404);
    }

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
    $token      = $request->input('nonce');
    $velocity   = $request->input('velocity');
    $errors     = $request->input('errors');
    $keystrokes = $request->input('keystrokes');

    // check results and, if valid, save them
    if($nonce = LectionNonce::validate($token, $velocity)) {

      $result = new LectionResult([
        'id_user'     => $user->id_user,
        'velocity'    => $velocity,
        'errors'      => $errors,
        'keystrokes'  => $keystrokes,
        'xp'          => $this->calculateXP($nonce),
      ]);

      $result->id_lection = isset($nonce->data['id_lection']) ? $nonce->data['id_lection'] : null;
      $result->id_exercise = isset($nonce->data['id_exercise']) ? $nonce->data['id_exercise'] : null;

      $result->save();

    } else {

      session()->flash('cheated', true);
    }

    // flash results to session (so that they can be used in show())
    session()->flash('velocity',      $velocity);
    session()->flash('error_amount',  $errors);
    session()->flash('keystrokes',    $keystrokes);
    session()->flash('xp',            LectionResult::getTodaysXP($user->id_user));

    return response('', 200);
  }
}
