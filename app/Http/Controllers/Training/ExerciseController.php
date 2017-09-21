<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\Exercise;
use App\Traits\CreateLectionNonce;

class ExerciseController extends Controller
{
  use CreateLectionNonce;

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
  public function showLection($exerciseId)
  {
    return view('training.app', ['dataURI' => "/exercise/$exerciseId", 'keyboardLayout' => 'de-de']);
  }

  /**
    * returns lection as JSON
    *
    * @param integer $exerciseId (external_id)
    * @return JSON | abort(404)
    */
  public function getLection($exerciseId)
  {
    $exercise = Exercise::where('id_exercise', $exerciseId)->first();

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
