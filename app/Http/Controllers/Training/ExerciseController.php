<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\Exercise;
use App\Traits\CreateLectionNonce, App\Traits\CreateAppView;

class ExerciseController extends Controller
{
  use CreateLectionNonce, CreateAppView;

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

    if($exercise) {

      if(Auth::user()->id_user !== $exercise->id_user && is_null($exercise->is_public)) {
        // exercise does not belong to user and is not publicly available
        abort(403);
      }

      $this->createLectionNonce($exercise->characterAmount);

      return [
        'meta' => [
          'mode' => 'block',     // to prepare content of exercise properly
        ],
        'lines' => $exercise->content,
      ];

    } else {      // exercise does not exists, throw 404

      abort(404);
    }
  }
}
