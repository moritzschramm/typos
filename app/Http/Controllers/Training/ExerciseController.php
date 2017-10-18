<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\Exercise;
use App\Models\LectionNonce;
use App\Traits\CreateAppView;

class ExerciseController extends Controller
{
  use CreateAppView;

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
    * shows training view
    *
    * @param integer $exerciseId (external_id)
    * @return view
    */
  public function showExercise($exerciseId)
  {
    return $this->createAppView("/exercise/$exerciseId", Auth::user());
  }

  /**
    * returns lection as JSON
    *
    * @param integer $exerciseId (external_id)
    * @return JSON | abort(404)
    */
  public function getExercise($exerciseId)
  {
    $exercise = Exercise::where('external_id', $exerciseId)->first();

    if(is_null($exercise)) {
      // exercise does not exist (anymore)
      abort(404);
    }

    if(Auth::user()->id_user !== $exercise->id_user && is_null($exercise->is_public)) {
      // exercise does not belong to user and is not publicly available
      abort(403);
    }

    LectionNonce::create($exercise->character_amount);
    $text = str_replace("\r\n", "\n", $exercise->content);
    $lines = explode("\n", wordwrap($text, 20, "\n", true));

    return [
      'lines' => $lines,
    ];
  }
}
