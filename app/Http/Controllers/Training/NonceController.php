<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NonceController extends Controller
{
  /**
    * generates the actual nonce and the timestamp
    *
    * @return string $nonce
    */
  public function generateNonce()
  {
    $nonce = session('nonce');

    if($nonce) {

      $nonce->nonce = generateSecureString(32);         // create 32 chars long token
      $nonce->timestamp = time();

      return $nonce->nonce;

    } else {

      abort(403);
    }
  }
}
