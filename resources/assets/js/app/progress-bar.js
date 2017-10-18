/****
  *** @NOTE REQUIRED jQuery
  *** progress-bar:
  *** updates the progress bar
  ***/

var pb_element;

/**
  * init
  */
function pb_init(loaded) {

  pb_element = $("#progressbar");

  loaded(app_modules.progressbar);
}

/**
  * update progressbar
  */
function pb_update() {

  var ratio = sequence.index / sequence.lines.length * 100;

  pb_element.css("width", ratio+"%");
}
