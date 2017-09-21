<?php

namespace App\Traits;

use App\Models\LectionNonce;

trait CreateLectionNonce {

  /**
    * Creates a lection nonce without generating a nonce and timestamp
    *
    * @param int $userId
    * @param int $characterAmount: the total amount of characters the lections will have
    * @param boolean $isLection (default false): used for xp calculation (lections always 10XP)
    * @return string $nonce
    */
  public function createLectionNonce($characterAmount, $isLection = false)
  {
    $nonce                    = new LectionNonce;
    $nonce->character_amount  = $characterAmount;
    $nonce->is_lection        = $isLection;

    session()->put('nonce', $nonce);
  }
}
