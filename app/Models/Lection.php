<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
  * Lections are predefined exercises not created by the user.
  * Every user can access lections in their locale (if available, fallback is
  * of course 'en'), which makes the lections static data.
  * The only reason they are stored inside the DB, is, because there needs
  * to be the correct character_amount given to the application (which can't
  * be send via the client since it is used to validate the results).
  * In the future, however, lections should be stored as a .json file
  * in the public directory of the app. The character_amount should be calculated
  * by a command only once (when lections change) and stored inside the database
  */

class Lection extends Model
{
  protected $table = 'lections';
  protected $primaryKey = 'id_lection';

  protected $fillable = [ 'id_lection', 'title', 'content', 'character_amount', 'locale',];
}
