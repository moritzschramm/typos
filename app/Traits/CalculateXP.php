<?php

namespace App\Traits;

trait CalculateXP {

  /**
    * calculates how much XP a user should get for finishing a lection/training
    * NOTE: lections give ALWAYS 10 XP, while the number depends on the
    * amount of characters in trainings and exercises
    * 200 characters == 10 XP
    * 257 characters == 13 XP (xp gets rounded)
    *
    * @param LectionNonce $nonce
    * @return integer $xp
    */
  public function calculateXP($nonce)
  {
    if($nonce) {

      if(isset($nonce->data['id_lection'])) {

        return 10;

      } else {

        return round($nonce->character_amount / 20.0);
      }

    } else {

      return 0;
    }
  }
}
