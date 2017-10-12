<?php

/**
  * Static sites (public sites)
  */
Route::group(['namespace' => 'Content'], function() {

  Route::get('/',           'ContentController@index');
  Route::get('/privacy',    'ContentController@privacy');

});

/**
  * Locale controller
  */
Route::group(['namespace' => 'Locale'], function() {

  Route::get('/locale/{locale}',      'LocaleController@setLocale');
});

/**
  * Authentication:
  * Login, Registration, Password resets
  */
Route::group(['namespace' => 'Auth'], function() {

  Route::get('/login',                      'LoginController@showLogin');
  Route::post('/login',                     'LoginController@login');

  Route::get('/register',                   'RegisterController@showRegister');
  Route::post('/register',                  'RegisterController@register');

  Route::get('/password/forgot',            'PasswordController@showForgotPassword');
  Route::post('/password/forgot',           'PasswordController@requestPasswordReset');

  Route::get('/password/reset',             'PasswordController@showResetPassword');
  Route::post('/password/reset',            'PasswordController@resetPassword');

  Route::get('/verify/{uuid}/{token}',      'VerifyController@verifyUser');

  Route::get('/logout',                     'LoginController@logout');

});

/**
  * Dashboard:
  * Dashboard, Statistics, Exercise
  */
Route::group(['namespace' => 'Dashboard'], function() {

  Route::get('/dashboard',            'DashboardController@showDashboard');

  Route::get('/exercise',             'ExerciseController@showAddExercise');
  Route::post('/exercise',            'ExerciseController@saveExercise');
  Route::get('/exercise/{id}/edit',   'ExerciseController@showEditExercise');
  Route::post('/exercise/{id}/edit',  'ExerciseController@editExercise');

  Route::get('/statistics',           'StatsController@showStats');
  Route::post('/stats/velocity',      'StatsController@velocityStats');
  Route::post('/stats/xp',            'StatsController@xpStats');
  Route::post('/stats/keystrokes',    'StatsController@keystrokesStats');

});

/**
  * Support:
  * Support and feedback
  */
Route::group(['namespace' => 'Support'], function() {

  Route::get('/support',            'SupportController@showSupport');
  Route::post('/support',           'SupportController@sendSupportRequest');

  Route::post('/feedback',          'FeedbackController@sendFeedback');

});

/**
  * Training:
  * Training, Lections, Exercises, Results
  */
Route::group(['namespace' => 'Training'], function() {

  Route::post('/results/upload',        'ResultController@upload');
  Route::get('/results/show',           'ResultController@show');

  Route::post('/training/nonce',        'NonceController@generateNonce');

  Route::get('/training',               'TrainingController@showTraining');
  Route::post('/training',              'TrainingController@getWords');

  Route::get('/lection/{lectionId}',    'LectionController@showLection');
  Route::post('/lection/{lectionId}',   'LectionController@getLection');

  Route::get('/exercise/{exerciseId}',  'ExerciseController@showExercise');
  Route::post('/exercise/{exerciseId}', 'ExerciseController@getExercise');

  Route::get('/trial',                  'TrialController@showApp');
  Route::post('/trial',                 'TrialController@getWords');
  Route::post('/trial/upload',          'TrialController@handleUpload');

});

/**
  * Preferences
  */
Route::group(['namespace' => 'Preferences'], function() {

  Route::get('/preferences',                    'PreferencesController@showPreferences');

  Route::post('/preferences/account/email',     'AccountController@changeEmail');
  Route::post('/preferences/account/reset',     'AccountController@deleteStats');
  Route::post('/preferences/account/delete',    'AccountController@deleteAccount');
  Route::post('/preferences/security/password', 'SecurityController@changePassword');
  Route::post('/preferences/app',               'AppPreferencesController@editAppPreferences');
});
