<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use DB;
use App\Traits\CreateLectionNonce, App\Traits\ValidateLectionNonce, App\Traits\CreateAppView;

class TrialController extends Controller
{
  use CreateLectionNonce, ValidateLectionNonce, CreateAppView;

  /**
    * show app view
    *
    * @return view
    */
  public function showApp()
  {
    session()->flash('notification', 'info.beta');

    return $this->createAppView('/trial', ['trial' => true]);
  }

  /**
    * return random words as JSON and create lection nonce
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
    $characterAmount = strlen(implode($words, ''));
    $this->createLectionNonce($characterAmount);

    return
    [
      'meta' => [
          'mode' => 'expand', // valid modes: expand, prepared and block (@see resources/assets/js/app/sequence.js)
          'resultURI' => '/trial/upload',   // overwrite default upload URI
        ],
      'lines' => $words,
    ];
  }

  /**
    * handle upload of results (does not store them)
    *
    * @param Request $request
    * @return Response (200)
    */
  public function handleUpload(Request $request)
  {
    $nonce     = session('nonce');
    $currentXP = 0;

    if($this->validateLectionNonce($nonce, $request->input('velocity'))) {

      $currentXP = session()->has('trial_xp') ? session('trial_xp') + 5 : 5;    // increment session xp by 5
      session(['trial_xp' => $currentXP]);
    }

    session()->flash('velocity',      $request->input('velocity'));
    session()->flash('error_amount',  $request->input('errors'));
    session()->flash('keystrokes',    $request->input('keystrokes'));
    session()->flash('xp',            $currentXP);

    return response('', 200);
  }
}
