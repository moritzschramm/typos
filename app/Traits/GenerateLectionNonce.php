<?php

namespace App\Traits;

use App\Models\LectionNonce;

trait GenerateLectionNonce {

  /**
    * Generates and stores a lection nonce.
    * This nonce should be passed to the javascript app
    * which sends it back when the results get uploaded.
    * The nonce as well as timestamps, the user's velocity
    * and the amount of characters will be used to determine
    * if a bot is trying to upload fake results
    *
    * @param int $characterAmount: the total amount of characters the lections will have
    * @return string $nonce
    */
  public function generateLectionNonce($userId, $characterAmount, $isLection = false)
  {
    $nonce = new LectionNonce([
      'id_user'           => $userId,
      'nonce'             => generateSecureString(),
      'character_amount'  => $characterAmount,
      'is_lection'        => $isLection,
      // timestamp will be added by laravel automatically
    ]);

    $nonce->save();

    return $nonce->nonce;
  }
}
