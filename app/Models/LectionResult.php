<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LectionResult extends Model
{
  protected $table = 'lection_results';
  protected $primaryKey = 'id_lection_result';

  protected $fillable = [ 'id_user', 'velocity', 'keystrokes', 'errors', 'xp' ];

  // database relationship
  public function user()
  {
    return $this->belongsTo(User::class, 'id_user', 'id_user');
  }
}
