<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\LectionNonce;
use App\Traits\CalculateXP;

class CalculateXPTest extends TestCase
{
  use CalculateXP;

  private $CHAR_AMOUNT = [234, 345, 200, 0, 10, 9, 20];
  private $XP = [12, 17, 10, 0, 1, 0, 1];

  /**
    * helper function to create lection nonce
    * NOTE: nonce has no timestamp/token
    *
    * @param (optional) array $data (default [])
    * @return LectionNonce $nonce
    */
  private function createNonce($chars, $data = [])
  {
    $nonce = new LectionNonce;
    $nonce->character_amount = $chars;
    $nonce->data = $data;

    return $nonce;
  }

  /**
    * tests if calculateXP returns correct results
    */
  public function testCalculateXP()
  {
    for($i = 0; $i < count($this->CHAR_AMOUNT); $i++) {

      $nonce = $this->createNonce($this->CHAR_AMOUNT[$i]);
      $xp = $this->calculateXP($nonce);
      $this->assertEquals($this->XP[$i], $xp);
    }

    $nonce = $this->createNonce(123, ['id_lection' => 1]);
    $xp = $this->calculateXP($nonce);
    $this->assertEquals(10, $xp);
  }
}
