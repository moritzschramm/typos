<?php

namespace App\Traits;

trait CreateAppView {

  /**
    * creats training app view
    *
    * @param string $dataURI: will be passed to app to retrieve sequence via XHR POST
    * @param array $data (optional): the data that will be passed to the view
    *        NOTE: $data will be merged with a preset array containing defaults
    * @return view
    */
  public function createAppView($dataURI, $data = array()) {

    # add additional data to $data array, $data parameter will overwrite any defaults from hardcoded array
    $data = array_merge([
      'dataURI'         => $dataURI,
      'keyboardLayout'  => 'de-de',
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
