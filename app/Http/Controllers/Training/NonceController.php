<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NonceController extends Controller
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
    * generates the actual nonce and the timestamp
    *
    * @return string $nonce
    */
  public function generateNonce()
  {
    $nonce = session('nonce');

    if($nonce) {

      $nonce->nonce = generateSecureString();
      $nonce->timestamp = time();

      return $nonce->nonce;

    } else {

      abort(403);
    }
  }
}
