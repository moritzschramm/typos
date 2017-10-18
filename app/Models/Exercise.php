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

  /**
    * checks if exercise contains any invalid characters
    *
    * @param string $content
    * @return null|string $invalidChars
    */
  public static function findInvalidCharacters($content)
  {
    $allowed = '\.ß+#*~-_,;:!"\'?§$%&()[]{}°<>|@€µ=äöü';
    $allowed_already_escaped = '\/ ';   # whitespace is intended
    $always_allowed = 'a-zA-Z0-9\r\n\t';
    $regex = '/(*UTF8)[^' . $always_allowed . $allowed_already_escaped . preg_quote($allowed) . ']/';
    # the (*UTF8) is necessary because it's 2017 and php still sucks _abysmally_ at unicode
    # @see https://stackoverflow.com/questions/1725227/preg-match-and-utf-8-in-php


    $match_counter = preg_match_all($regex, $content, $matches);

    return $match_counter === 0 ? null : implode($matches[0]);
  }

  // database relationship
  public function user()
  {
    return $this->belongsTo(User::class, 'id_user', 'id_user');
  }
}
