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

  /**
    * Creates a uuid for a new user. Checks if the uuid already exists
    *
    * @return string $uuid
    */
  public static function uuid()
  {
    do {

      $uuid = uuidv4();
      $exists = User::where('uuid', $uuid)->first();

    } while($exists);

    return $uuid;
  }

  public function lectionResults()
  {
    return $this->hasMany(LectionResult::class, 'id_user', 'id_user');
  }

  public function exercises()
  {
    return $this->hasMany(Exercise::class, 'id_user', 'id_user');
  }
}
