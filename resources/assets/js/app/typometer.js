/****
  *** @NOTE requires jQuery
  *** @NOTE requires keyboard_listener.js
  *** @NOTE requires infobar.js
  *** typometer:
  *** measures key presses per minute
  ***/

var tm_startTime;             // used for calculating time difference
var tm_currentTime;
var tm_velocity       = 0;    // current velocity (keystrokes per minute)
var tm_keystrokes     = 0;    // total amount of keystrokes a user has made

var tm_intervalTime   = 2500; // (in miliseconds) interval time for calling tm_calculate
var tm_intervalId;      // id returned by setInterval() (used for clearInterval())


/**
  * initialization
  */
function tm_init(loaded) {

  loaded("typometer");
}

/**
  * should be called when user is able to make first input
  * sets tm_startTime and starts interval
  */
function tm_start() {

  tm_startTime  = (new Date()).getTime();
  tm_intervalId = setInterval(tm_calculate, tm_intervalTime);     // avg key presses per minute get updated on every keystroke or every 2.5 seconds
}

/**
  * stop typometer
  * velocity and keystrokes will NOT get resetted
  */
function tm_stop() {

  clearInterval(tm_intervalId);
}

/**
  * should be called when user presses a key
  * increments tm_keystrokes by one and updates velocity and UI
  */
function tm_keystroke() {

  tm_keystrokes++;
  tm_calculate();
}

/**
  * calculates current velocity by dividing the amount of keystrokes
  * through time difference (and converts it to keystrokes per minute)
  * updates UI
  */
function tm_calculate() {

  tm_currentTime = (new Date()).getTime();

  tm_velocity = tm_keystrokes / ((tm_currentTime - tm_startTime) / (60 * 1000));

  ib_updateBar();
}
