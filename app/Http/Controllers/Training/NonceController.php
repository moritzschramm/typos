<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\LectionNonce;

class NonceController extends Controller
{
  /**
    * generates the actual nonce and the timestamp
    *
    * @return string $nonce
    */
  public function generateNonce()
  {
    if(LectionNonce::exists()) {

      return LectionNonce::generateToken();

    } else {

      abort(403);
    }
  }
}
