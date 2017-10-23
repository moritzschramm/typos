<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\LectionResult;

use Auth;

class StatsController extends Controller
{
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
    * show stats index view
    *
    * @return view
    */
  public function showStats(Request $request)
  {
    $view = $request->filled('view') ? $request->input('view') : 'velocity';

    return view('dashboard.stats', ['view' => $view]);
  }

  /**
    * return velocity stats of last $limit results
    *
    * @param Request
    * @return JSON
    */
  public function velocityStats(Request $request)
  {
    $request->validate(['limit' => 'integer']);   # NOTE: limit is optional! Though, if no limit is specified _all_ results will be returned

    $limit = $request->input('limit');
    $id_user = Auth::user()->id_user;

    return [
      'limit' => $limit,
      'data'  => LectionResult::getVelocity($id_user, $limit),
    ];
  }

  /**
    * return velocity stats for given time range
    *
    * @param Request
    * @return JSON
    */
  public function xpStats(Request $request)
  {
    $request->validate([
      'from' => 'required|date_format:Y-m-d',
      'to'   => 'required|date_format:Y-m-d',
    ]);

    $from     = $request->input('from');
    $to       = $request->input('to');
    $id_user  = Auth::user()->id_user;

    return [
      'data' => LectionResult::getXP($id_user, $from, $to),
    ];
  }

  /**
    * return velocity stats for given time range
    *
    * @param Request
    * @return JSON
    */
  public function keystrokesStats(Request $request)
  {
    $id_user = Auth::user()->id_user;

    if($request->filled('from') && $request->filled('to')) {

      $request->validate([
        'from' => 'required|date_format:Y-m-d',
        'to'   => 'required|date_format:Y-m-d',
      ]);

      $from = $request->input('from');
      $to   = $request->input('to');

      return [
        'keys' => ['keystrokes', 'errors'],
        'data' => LectionResult::getKeystrokesByDate($id_user, $from, $to),
      ];

    } else {

      $request->validate(['limit' => 'integer']); # limit is optional, if no limit is specified, all results will be returned

      $limit = $request->input('limit');

      return [
        'keys'  => ['keystrokes', 'errors'],
        'limit' => $limit,
        'data'  => LectionResult::getKeystrokesByLection($id_user, $limit),
      ];
    }
  }
}
