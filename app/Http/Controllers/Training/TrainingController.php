<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB, Auth;

use App\Models\LectionNonce;

use App\Traits\CreateAppView;

class TrainingController extends Controller
{
  use CreateAppView;

  /**
    * Middlewares:
    *  - auth
    *
    */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
    * show training view
    *
    * @return view
    */
  public function showTraining()
  {
    return $this->createAppView('/training', Auth::user());
  }

  /**
    * return words for training
    *
    * @return JSON
    */
  public function getWords()
  {
    $locale = session('app_locale');
    $table  = 'words_' . $locale;

    $number_of_rows = DB::table($table)->select(DB::raw('COUNT(*) as count'))->first()->count;

    // create a list of 10 random positions
    $position_list = [];

    $word_amount = 10;
    for($i = 0; $i < $word_amount; $i++) {

      $position_list[] = rand(1, $number_of_rows);
    }

    // get 10 random words from database
    $query = 'SELECT word FROM ' . $table . ' WHERE id IN (' . implode(',', $position_list) . ') LIMIT ' . $word_amount;
    $words_raw = DB::select($query);
    $words = [];

    foreach($words_raw as $word_raw) {

      $words[] = $word_raw->word;
    }

    // store Lection nonce in session
    $charAmount = mb_strlen(implode($words, ''));
    LectionNonce::create($charAmount);

    // return array (laravel will automatically encode it to JSON)
    return [
      'lines' => $words,
    ];
  }
}
