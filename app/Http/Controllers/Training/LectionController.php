<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\Lection;
use App\Models\LectionNonce;

use App\Traits\CreateAppView;

class LectionController extends Controller
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

    if(is_null($lection)) {

      abort(404);
    }

    LectionNonce::create($lection->character_amount, ['id_lection' => $lection->id_lection]);

    return [
      'lines' => explode("\n", $lection->content),
    ];
  }
}
