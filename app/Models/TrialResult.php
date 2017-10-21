<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrialResult extends Model
{
  protected $table = 'trial_results';
  protected $primaryKey = 'id_trial_result';

  protected $fillable = [ 'velocity', 'keystrokes', 'errors', 'is_public' ];

}
