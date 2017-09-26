<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
  protected $table = 'user_preferences';
  protected $primaryKey = 'id_user_preferences';

  protected $fillable = [ 'id_user', 'keyboard', 'xp_goal', 'show_assignment',
                          'show_divider', 'show_keyboard'];

  // default preferences
  public static function defaults($locale = 'en')
  {
    return [
      'keyboard'        => 'de-de',
      'xp_goal'         => 30,
      'show_assignment' => false,
      'show_divider'    => false,
      'show_keyboard'   => true,
    ];
  }

  // database relationship
  public function user()
  {
    $this->belongsTo(User::class, 'id_user', 'id_user');
  }

  /**
    * extracts locale from keyboard layout
    *
    * @return string $locale
    */
  public function getKeyboardLocale()
  {
    return substr($this->keyboard, 0, 2);   // cuts 'de-de' to 'de'
  }
}
