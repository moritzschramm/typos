<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\LectionNonce;

use DB, Validator;
use App\Traits\CreateAppView, App\Traits\ProfanityFilter;
use App\Models\TrialResult;

class TrialController extends Controller
{
  use CreateAppView, ProfanityFilter;

  const CONTENT_MAX_LENGTH  = 250;
  const LINE_MAX_LENGTH     = 20;

  /**
    * show app view
    *
    * @return view
    */
  public function showApp()
  {
    session()->forget('notification-success');    // delete 'result published' message
    session()->flash('notification', 'info.beta');

    return $this->createAppView('/trial', session('app_locale'), ['trial' => true]);
  }

  /**
    * returns custom results view;
    * shows table of published TrialResults as well as user's current results
    *
    * @return view
    */
  public function showResults()
  {
    // in case page gets reloaded, reflash the session storage
    session()->reflash();

    if( ! session()->has('velocity', 'error_amount', 'keystrokes', 'score')) {

      return abort(404);
    }

    return response()->view('training.trialResults', [
      'velocity'      => session('velocity'),
      'error_amount'  => session('error_amount'),
      'keystrokes'    => session('keystrokes'),
      'score'         => session('score'),
      'cheated'       => session('cheated'),
      'results'       => TrialResult::bestOfLast24h(),
    ],
    session('cheated') ? 418 : 200);  // set status code
  }

  /**
    * return random words as JSON and create lection nonce
    *
    * @return JSON
    */
  public function getWords()
  {
    // $locale = session('app_locale');
    // switch($locale) {
    //   case 'de': $locale = 'de_DE'; break;
    //   case 'en': $locale = 'en_US'; break;
    //   default: $locale = 'en_US';
    // }

    $faker      = \Faker\Factory::create();
    $text       = $faker->realText(self::CONTENT_MAX_LENGTH);
    $words      = explode("\n", wordwrap($text, self::LINE_MAX_LENGTH, "\n", true));
    $charAmount = mb_strlen(implode($words, ''));  // mb_strlen($text) returns wrong result, $words has fewer chars because some \n were removed

    // store LectionNonce in session
    LectionNonce::create($charAmount);

    return
    [
      'meta' => [
        'uploadResultURI'     => '/trial/upload',   // overwrite default upload URI
        'showResultURI'       => '/trial/results',
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
    $velocity   = $request->input('velocity');
    $keystrokes = $request->input('keystrokes');
    $errors     = $request->input('errors');
    $score      = TrialResult::calculateScore($velocity, $keystrokes, $errors);

    if(LectionNonce::validate($request->input('nonce'), $request->input('velocity'))) {

      $result = new TrialResult([
        'velocity'    => $velocity,
        'keystrokes'  => $keystrokes,
        'errors'      => $errors,
        'score'       => $score,
        'is_public'   => false,
      ]);
      $result->save();

      session()->flash('trial_result_id', $result->id_trial_result);
      session()->flash('is_public',       $result->is_public);

    } else {

      session()->flash('cheated', true);
      session()->flash('is_public', true);
    }

    session()->flash('velocity',      $velocity);
    session()->flash('keystrokes',    $keystrokes);
    session()->flash('error_amount',  $errors);
    session()->flash('score',         $score);

    return response('', 200);
  }

  /**
    * publish TrialResult (that is currently in session) with given nickname
    *
    * @return redirect (back)
    */
  public function publishResults(Request $request)
  {
    session()->reflash();

    if( ! session()->has('trial_result_id')) {

      abort(404);
    }

    $validator = Validator::make($request->all(), [
      'nickname' => 'required|alpha_dash|max:30',
    ], [
      'required'    => 'errors.required',
      'alpha_dash'  => 'errors.alpha_dash',
      'max'         => 'errors.max',
    ]);

    $validator->after(function($validator) use ($request) {

      if(session('cheated')) {

        $validator->errors()->add('nickname', 'training.results.cheated');

      } else if($this->isProfane($request->input('nickname'))) {

        $validator->errors()->add('nickname', 'errors.profanity');
      }
    });

    if($validator->fails()) {

      return back()->withInput()->withErrors($validator);

    } else {

      $id = session('trial_result_id');
      $result = TrialResult::where('id_trial_result', $id)->first();

      if(is_null($result)) {

        abort(404);
      }

      $result->nickname   = $request->input('nickname');
      $result->is_public  = true;
      $result->update();

      session()->flash('is_public', true);
      session()->put(['nickname' => $result->nickname]);

      session()->forget('errors');         // clear previous errors

      return back()->with('notification-success', 'training.results.published');
    }
  }
}
