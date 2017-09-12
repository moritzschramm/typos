<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LectionNonce extends Model
{
  protected $table = 'lection_nonces';
  protected $primaryKey = 'id_lection_nonce';

  protected $fillable = [ 'id_user', 'nonce', 'character_amount', 'is_lection' ]; 

  public function user()
  {
    return $this->belongsTo(User::class, 'id_user', 'id_user');
  }
}
