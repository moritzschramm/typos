<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
  * Exercises are user-created lections. Users can create exercises to make
  * their own custom training. Since XP is calculated by the character amount
  * of an exercise, users can't cheat by creating short exercises.
  * Exercises are by default private to the user who created it, but can be
  * made public to view by anyone.
  * Note that 'is_public' is a datetime, containing either NULL (exercise is
  * private) or the datetime on which it was made public
  */

class Exercise extends Model
{
  protected $table = 'exercises';
  protected $primaryKey = 'id_exercise';

  protected $fillable = [ 'external_id', 'id_user', 'content', 'character_amount', 'is_public' ];

  /**
    * create ID for new exercise (external_id)
    * check if ID already exists
    *
    * @return string ID
    */
  public static function newId()
  {
    do {

      $id = generateSecureString();
      $exists = Exercise::where('external_id', $id)->first();

    } while($exists);

    return $id;
  }

  // database relationship
  public function user()
  {
    return $this->belongsTo(User::class, 'id_user', 'id_user');
  }
}
