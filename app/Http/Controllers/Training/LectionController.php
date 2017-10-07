<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\Lection;
use App\Traits\CreateLectionNonce, App\Traits\CreateAppView;

class LectionController extends Controller
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
    * @param integer $lectionId (external_id)
    * @return view
    */
  public function showLection($lectionId)
  {
    return $this->createAppView("/lection/$lectionId", Auth::user());
  }

  /**
    * returns lection as JSON
    *
    * @param integer $lectionId (external_id)
    * @return JSON | abort(404)
    */
  public function getLection($lectionId)
  {
    $locale = Auth::user()->locale;

    $lection = Lection::where([
      ['external_id', '=', $lectionId],
      ['locale',      '=', $locale],
    ])->first();

    if($lection) {

      $this->createLectionNonce($lection->characterAmount, $isLection=true);

      return [
        'meta' => [
          'mode' => 'prepared',     // content of lection is already prepared (lines < 20 chars)
        ],
        'lines' => explode("\n", $lection->content),
      ];

    } else {      // if lection does not exists, throw 404

      abort(404);
    }
  }
}
