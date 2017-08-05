<?php

/**
  * Static sites (public sites)
  */
Route::group(['namespace' => 'Static'], function() {

  Route::get('/', 'StaticContentController@index');

});

/**
  * Authentication:
  * Login, Registration, Password resets
  */
Route::group(['namespace' => 'Auth'], function() {

  Route::get('/login',              'LoginController@showLogin');
  Route::post('/login',             'LoginController@login');

  Route::get('/register',           'RegisterController@showRegister');
  Route::post('/register',          'RegisterController@register');

  Route::get('/password/forgot',    'PasswordController@showForgotPassword');
  Route::post('/password/forgot',   'PasswordController@requestPasswordReset');

  Route::get('/password/reset',     'PasswordController@showResetPassword');
  Route::post('/password/reset',    'PasswordController@resetPassword');

});

/**
  * Dashboard:
  * Dashboard, Statistics
  */
Route::group(['namespace' => 'Dashboard'], function() {

  Route::get('/dashboard',          'DashboardController@showDashboard');
  Route::get('/statistics',         'StatsController@showStats');

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
  * Training, Lections, Exercises
  */
Route::group(['namespace' => 'Training'], function() {

  Route::get('/training',               'TrainingController@showTraining');
  Route::post('/training',              'TrainingController@getWords');

  Route::get('/lection/{lectionId}',    'LectionController@showLection');
  Route::post('/lection/{lectionId}',   'LectionController@getLection');

  Route::get('/exercise/{exerciseId}',  'ExerciseController@showExercise');
  Route::post('/exercise/{exerciseId}', 'ExerciseController@getExercise');

});

/**
  * Exercises
  */
Route::group(['namespace' => 'Exercise'], function() {

  Route::get('/exercise',               'ExerciseController@showAddExercise');
  Route::post('/exercise',              'ExerciseController@saveExercise');

});

/**
  * Preferences
  */
Route::group(['namespace' => 'Preferences'], function() {

  Route::get('/preferences',                    'PreferencesController@showOverview');
  Route::get('/preferences/account',            'AccountController@showAccount');
  Route::get('/preferences/security',           'SecurityController@showSecurity');
  Route::get('/preferences/app',                'AppController@showAppPreferences');

  Route::post('/preferences/account/email',     'AccountController@changeEmail');
  Route::post('/preferences/account/reset',     'AccountController@resetAccount');
  Route::post('/preferences/account/delete',    'AccountController@deleteAccount');
  Route::post('/preferences/security/password', 'SecurityController@changePassword');
  Route::post('/preferences/app',               'AppController@editAppPreferences');
});
