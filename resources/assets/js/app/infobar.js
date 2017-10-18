/****
  *** @NOTE requires jQuery
  *** @NOTE requires app.js
  *** @NOTE requires typometer.js
  *** infobar:
  *** updates the info bar (current speed, error count)
  ***/

var ib_velocityElement;
var ib_errorCountElement;
var ib_errorRatioElement;

/**
  * initialization
  */
function ib_init(loaded) {

  ib_velocityElement    = $("#velocity");
  ib_errorCountElement  = $("#errorCount");
  ib_errorRatioElement  = $("#errorRatio");

  loaded(app_modules.infobar);
}

/**
  * update all text elements
  */
function ib_updateBar() {

  ib_velocityElement.html(tm_velocity.toFixed(1));
  ib_errorCountElement.html(app_errorCount);
  ib_errorRatioElement.html(( Math.min(app_errorCount / tm_keystrokes, 1) * 100.0).toFixed(1));
}
