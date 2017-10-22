<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrialResult extends Model
{
  protected $table = 'trial_results';
  protected $primaryKey = 'id_trial_result';

  protected $fillable = [ 'velocity', 'keystrokes', 'errors', 'score', 'is_public' ];

  /**
    * calculates a total score for given results
    *
    * @param float $velocity
    * @param integer $keystrokes
    * @param integer $errors
    * @return integer $score
    */
  public static function calculateScore($velocity, $keystrokes, $errors)
  {
    return round(($velocity * ($keystrokes / max(1, $errors))) / 100);
  }

  /**
    * returns the best 30 of the last 24h
    *
    * @param (optional) integer $limit
    * @return Collection (TrialResult)
    */
  public static function bestOfLast24h($limit = 30)
  {
    return TrialResult::where('created_at', '>=', date('Y-m-d H:i:s', time() - 24 * 3600))
                        ->where('is_public', 1)
                        ->orderBy('score', 'DESC')
                        ->limit($limit)
                        ->get();
  }
}
