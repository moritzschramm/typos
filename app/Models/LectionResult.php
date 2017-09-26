<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;

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

  public static function getKeystrokesByDate($id_user, $from, $to)
  {
    $from = "'$from'";
    $to   = "'$to'";

    $results =
    LectionResult::select(DB::raw('id_user, SUM(keystrokes) keystrokes, SUM(erros) errors, DATE(created_at) date'))   # sum keystrokes and errors, convert created_at to date
      ->groupBy(DB::raw('id_user, DATE(created_at)'))                                                                 # group by id_user and created_at (as date)
      ->havingRaw('`date` >= ' . $from . ' AND `date` <= ' . $to . ' AND `id_user` = ' . $id_user)                    # return only results where date is in range and user id matches parameter
      ->get();

    $data = [];

    foreach($results as $result) {

      $data[$result->date] = [
        'keystrokes'  => $result->keystrokes,
        'errors'      => $result->errors,
      ];
    }

    return $data;
  }

  /**
    * get last $limit results with value of keystrokes and errors
    * NOTE: if $limit is NULL, no limit will be set
    *
    * @param integer $id_user
    * @param integer $limit (optional)
    * @return array $results (key: datetime, value: velocity)
    */
  public static function getKeystrokesByLection($id_user, $limit = NULL)
  {
    $query = LectionResult::select('id_user', 'keystrokes', 'errors', 'created_at')
      ->where('id_user', $id_user)
      ->latest();

    if( ! is_null($limit)) $query = $query->limit($limit);

    $result = $query->get();

    $data = [];

    foreach($results as $result) {

      $data[$result->created_at] = [
        'keystrokes'  => $result->keystrokes,
        'errors'      => $result->errors,
      ];
    }

    return $data;
  }

  /**
    * get last $limit results with value of velocity
    * NOTE: if $limit is NULL, no limit will be set
    *
    * @param integer $id_user
    * @param integer $limit (optional)
    * @return array $results (key: datetime, value: velocity)
    */
  public static function getVelocity($id_user, $limit = NULL)
  {
    $query = LectionResult::select('id_user', 'velocity', 'created_at')
      ->where('id_user', $id_user)
      ->latest();

    if( ! is_null($limit)) $query = $query->limit($limit);

    $result = $query->get();

    $data = [];

    foreach($results as $result) {

      $data[$result->created_at] = $result->velocity;
    }

    return $data;
  }

  /**
    * returns array of cumulative xp on a day between $from and $to
    * for a user with $id_user
    *
    * @param integer $id_user
    * @param string $from (date, e.g. 2017-09-25)
    * @param string $to (date)
    * @return array $data (key: date, value: xp)
    */
  public static function getXP($id_user, $from, $to)
  {
    $from = "'$from'";
    $to   = "'$to'";

    $results = LectionResult::select(DB::raw('id_user, SUM(xp) xp, DATE(created_at) date'))         # sum xp, convert created_at to date
      ->groupBy(DB::raw('id_user, DATE(created_at)'))                                               # group by id_user and created_at (as date)
      ->havingRaw('`date` >= ' . $from . ' AND `date` <= ' . $to . ' AND `id_user` = ' . $id_user)  # return only results where date is in range and user id matches parameter
      ->get();

    $data = [];

    foreach($results as $result) {

      $data[$result->date] = $result->xp;
    }

    return $data;
  }

  /**
    * returns the cumulative xp of a user with $id_user on today's date
    *
    * @param integer $id_user
    * @return integer $xp
    */
  public static function getTodaysXP($id_user)
  {
    $today = "'" . date('Y-m-d') . "'";   # e.g. '2017-09-25'

    return LectionResult::select(DB::raw('id_user, SUM(xp) xp, DATE(created_at) today')) # sum xp, convert created_at to date
                  ->groupBy(DB::raw('id_user, DATE(created_at)'))                        # group by id_user and created_at (as date)
                  ->havingRaw('`today` = ' . $today . ' AND `id_user` = ' . $id_user)    # return only results where date = today and user id matches parameter
                  ->first()->xp;                                                         # get first row (there is only one) and return value of column xp
  }
  
}
