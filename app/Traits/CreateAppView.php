<?php

namespace App\Traits;

trait CreateAppView {

  /**
    * creats training app view
    *
    * @param string $dataURI: will be passed to app to retrieve sequence via XHR POST
    * @param string|User $userOrLocale: a user object (if user is logged in) or locale string (e.g. for trial)
    * @param array $data (optional): the data that will be passed to the view
    *        NOTE: $data will be merged with a preset array containing defaults
    * @return view
    */
  public function createAppView($dataURI, $userOrLocale, $data = array()) {

    if($userOrLocale instanceof \App\Models\User) {   # argument is instance of User class

      $preferences = $userOrLocale->preferences;

      $data['keyboardLayout'] = $preferences->keyboard;
      $data['setting'] = [
        'assignment'  => $preferences->show_assignment,
        'divider'     => $preferences->show_divider,
        'keyboard'    => $preferences->show_keyboard,
      ];

    } else {  # argument is a locale string

      switch($userOrLocale) {

        case 'en':  $layout = 'en-us'; break;
        case 'de':  $layout = 'de-de'; break;
        default:    $layout = 'en-us'; break;
      }

      $data['keyboardLayout'] = $layout;
    }


    # add additional data to $data array, $data parameter will overwrite any defaults from hardcoded array
    $data = array_merge([
      'dataURI'         => $dataURI,
      'keyboardLayout'  => 'en-us',
      'setting' => [
        'assignment'  => 'false',
        'divider'     => 'false',
        'keyboard'    => 'true',
      ],
      'trial'           => false,
    ], $data);

    return view('training.app', $data);
  }
}
