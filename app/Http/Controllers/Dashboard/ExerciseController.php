<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator, Auth;
use App\Models\Exercise;

class ExerciseController extends Controller
{
  /**
    * Middlewares:
    * - auth
    */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
    * shows view to create exercise
    *
    * @return view
    */
  public function showAddExercise()
  {
    return view('dashboard.exercise');
  }

  /**
    * shows view to edit exercise after checking
    * if exercise exists and user has permissions
    * to edit exercise
    *
    * @return view|abort
    */
  public function showEditExercise($id)
  {
    $exercise = Exercise::where('external_id', $id)
                          ->where('id_user', Auth::user()->id_user)
                          ->first();

    if(is_null($exercise)) {

      abort(404);
    }

    return view('dashboard.exercise', [
      'edit'    => true,
      'id'      => $id,
      'title'   => $exercise->title,
      'content' => $exercise->content,
    ]);
  }

  /**
    * validates input and creates exercise
    *
    * @return redirect
    */
  public function saveExercise(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'title'   => 'required|max:' . config('database.stringLength'),
      'content' => 'required',
    ], [
      'required' => 'errors.required',
      'max'      => 'errors.max',
    ]);

    if($validator->fails()) {

      return back()->withInput()->withErrors($validator);

    } else {

      $exercise = new Exercise;
      $exercise->id_user          = Auth::user()->id_user;
      $exercise->external_id      = Exercise::newId();
      $exercise->title            = $request->input('title');
      $exercise->content          = $request->input('content');
      $exercise->character_amount = strlen($request->input('content'));
      $exercise->is_public        = NULL;
      $exercise->save();

      return redirect('/dashboard?view=exercises')->with('notification-success', 'exercise.created');
    }
  }

  /**
    * validates input, checks if user has permission to edit and saves changes
    *
    * @return redirect|abort
    */
  public function editExercise($id, Request $request)
  {
    $exercise = Exercise::where('external_id', $id)
                          ->where('id_user', Auth::user()->id_user)
                          ->first();

    if(is_null($exercise)) {

      abort(404);
    }

    $validator = Validator::make($request->all(), [
      'title'   => 'required|max:' . config('database.stringLength'),
      'content' => 'required',
    ], [
      'required'  => 'errors.required',
      'max'       => 'errors.max',
    ]);

    if($validator->fails()) {

      return back()->withInput()->withErrors($validator);

    } else {

      $exercise->title            = $request->input('title');
      $exercise->content          = $request->input('content');
      $exercise->character_amount = strlen($reques->input('content'));
      $exercise->is_public        = NULL;
      $exercise->update();

      return redirect('/dashboard?view=exercises')->with('notification-success', 'exercises.edited');
    }
  }

  /**
    * delete exercise
    *
    * @param string id: external_id of exercise
    * @param Request
    * @return redirect
    */
  public function deleteExercise($id, Request $request)
  {
    $exercise = Exercise::where('external_id', $id)
                          ->where('id_user', Auth::user()->id_user)
                          ->first();

    if(is_null($exercise)) {

      abort(404);
    }

    $exercise->delete();

    return redirect('/dashboard?view=exercises')
            ->with('notification-success', 'Exercise deleted.');
  }
}
