<?php

namespace App\Models;

/**
  * NOTE: this model is only used in the session (since it's only in use for
  * a short period of time and does store only temporary data)
  * A LectionNonce gets created when the user starts a lection/training.
  * This nonce should be passed to the javascript app
  * which sends it back when the results get uploaded (after that the nonce
  * can be deleted).
  * The nonce as well as timestamps, the user's velocity
  * and the amount of characters will be used to determine
  * if a bot is trying to upload fake results.
  *
  * Also note that the nonce will be created first without an actual nonce and
  * timestamp, but to store the character amount. When the user makes his first
  * input, the nonce and timestamp should be assigned.
  */

class LectionNonce
{
  public $id_user;
  public $nonce;
  public $character_amount;
  public $timestamp;
  public $is_lection;
}
