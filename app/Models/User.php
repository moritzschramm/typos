<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use Notifiable;

  protected $table = 'users';
  protected $primaryKey = 'id_user';

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
      'password', 'remember_token', 'token',
  ];

  public function lectionNonces()
  {
    return $this->hasMany(LectionNonce::class, 'id_user', 'id_user');
  }

  public function lectionResults()
  {
    return $this->hasMany(LectionResult::class, 'id_user', 'id_user');
  }
}
