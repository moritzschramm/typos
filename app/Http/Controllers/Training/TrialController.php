<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\LectionNonce;

use DB;
use App\Traits\CreateAppView;
use Illuminate\Support\Facades\Log;


class TrialController extends Controller
{
  use CreateAppView;

  /**
    * show app view
    *
    * @return view
    */
  public function showApp()
  {
    session()->flash('notification', 'info.beta');

    return $this->createAppView('/trial', session('app_locale'), ['trial' => true]);
  }

  /**
    * return random words as JSON and create lection nonce
    *
    * @return JSON
    */
  public function getWords()
  {
    /*$locale = session('app_locale');
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
    }*/

    $locale = session('app_locale');
    switch($locale) {
      case 'de': $locale = 'de_DE'; break;
      case 'en': $locale = 'en_US'; break;
      default: $locale = 'en_US';
    }

    $faker      = \Faker\Factory::create($locale);
    $text       = $faker->realText(200);
    $words      = explode("\n", wordwrap($text, 20, "\n", true));
    $charAmount = strlen(implode($words, ''));  // strlen($text) returns wrong result, $words has fewer chars because some \n were removed

    Log::info(strlen(implode($words,'')));
    Log::info('text:'.$text);

    // store LectionNonce in session
    LectionNonce::create($charAmount);

    return
    [
      'meta' => [
        'uploadResultURI'     => '/trial/upload',   // overwrite default upload URI
        'showResultURI'       => '/results/show',
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
    $currentXP = 0;

    if(LectionNonce::validate($request->input('nonce'), $request->input('velocity'))) {

      $currentXP = session()->has('trial_xp') ? session('trial_xp') + 5 : 5;    // increment session xp by 5
      session(['trial_xp' => $currentXP]);

    } else {

      session()->flash('cheated', true);
    }

    session()->flash('velocity',      $request->input('velocity'));
    session()->flash('error_amount',  $request->input('errors'));
    session()->flash('keystrokes',    $request->input('keystrokes'));
    session()->flash('xp',            $currentXP);

    return response('', 200);
  }
}
