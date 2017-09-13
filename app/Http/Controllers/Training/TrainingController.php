<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB, Auth;

use App\Traits\GenerateLectionNonce;

class TrainingController extends Controller
{
  use GenerateLectionNonce;

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
    return view('training.app', ['dataURI' => '/training', 'keyboardLayout' => 'de-de']);
  }

  /**
    * return words for training
    *
    * @return JSON
    */
  public function getWords()
  {
    $number_of_rows = DB::table('words_de')->select(DB::raw('COUNT(*) as count'))->first()->count;

    // create a list of 10 random positions
    $position_list = [];

    for($i = 0; $i < 10; $i++) {

      $position_list[] = rand(1, $number_of_rows);
    }

    // get 10 random words from database
    $query = 'SELECT word FROM words_de WHERE id IN (' . implode(',', $position_list) . ') LIMIT 10';
    $words_raw = DB::select($query);
    $words = [];

    foreach($words_raw as $word_raw) {

      $words[] = $word_raw->word;
    }

    // generate a lection nonce
    $nonce = $this->generateLectionNonce(Auth::user()->id_user, count(implode($words, ' ')));

    // return array (laravel will automatically encode it to JSON)
    return
    [
      'meta' => [
          'mode' => 'expand', // valid modes: expand, prepared and block (@see resources/assets/js/app/sequence.js)
          'nonce' => $nonce,
        ],
      'lines' => $words,
    ];
  }
}
