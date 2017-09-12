/****
  *** @NOTE requires jQuery
  *** @NOTE requires keyboard_builder.js
  *** @NOTE requires keyboard_actions.js
  *** @NOTE requires keyboard_listener.js
  *** @NOTE requires display.js
  *** @NOTE requires typometer.js
  *** app:
  *** reacts to user input and changes display accordingly
  *** highlights next key to press
  ***/

var STATE_LOADING     = 0;    // app is loading and building resources (keyboard layout, sequences, etc.)
var STATE_IDLE        = 1;    // waiting for first user input (app will start AFTER user is ready (i.e. has made an input))
var STATE_RUNNING     = 2;    // user works through sequences
var STATE_FINISHED    = 3;    // user has finished typing all lines

var app_state       = STATE_LOADING;    // current app state
var app_inError     = false;            // if the there is currently some wrong input which has to be deleted before the user can type again
var app_errorCount  = 0;                // amount of errors made by user
var app_nonce       = "";               // retrieved via sequence, should be submitted when uploading results

var app_silentKeys = ["Shift", "AltGraph", "Alt", "Control"];    // keys that should not be printed to display

// modules that are essential for the app,
// loaded via app_moduleCallback (typically injected into _init() functions of other scripts)
var required_modules = [
    "keyboard", "display", "sequence", "typometer", "misc", "infobar"
];

function app_moduleCallback(moduleName) {

  var index = required_modules.indexOf(moduleName);   // find module

  if(index === -1) {    // module not found

    console.error("Loaded unknown module");

  } else {

  // remove from module list
  required_modules.splice(index, 1);

    if(required_modules.length === 0) { // if every module is loaded, change state to idle

      app_changeState(STATE_IDLE);
    }
  }
}

function app_changeState(state) {

  app_state = state;

  switch (state) {

    case STATE_IDLE:

      // hide loader
      $("#loader").hide();
      // show info
      dp_setNormalText("Beliebige Taste drücken");

      break;

    case STATE_RUNNING:

      // start typometer
      tm_start();

      break;

    case STATE_FINISHED:

      // show loader
      $("#loader").show();

      // stop typometer
      tm_stop();

      // upload results
      // if successful, redirect
      app_uploadResults();

      break;

    default: break;
  }
}

/**
  * listener for pressed key
  * called in keyboard_listener.js
  *
  * @param string key: the key that was pressed
  * @param string keyId: id of the key
  */
function app_keyPressed(key, keyId) {

  // in case the app is not running yet
  if(app_state === STATE_IDLE) {

    app_startLection();
    return;

  } else if(app_state === STATE_FINISHED) {

    return;
  }

  // check if key is a special key, return if so
  if(app_handleSpecialKeyPress(key, keyId)) {

    return;
  }

  key = app_replaceSpecialChar(key);

  var nextChar = dp_normal.charAt(0);

  if(key === nextChar && !app_inError) {  // correct key and currently no wrong input

    // cut line, remove first character old line
    var line = dp_normal.substring(1, dp_normal.length);

    if(line.length === 0) {   // line was completely retyped

      app_nextLine();
      return;
    }

    // add key to correct keys, replace " " with "_" to make space visible
    var correctChar = dp_normal.charAt(0) === " " ? "_" : dp_normal.charAt(0);
    dp_setGreenText(dp_green + correctChar);
    // set new line content
    dp_setNormalText(line);

    // unhighlight current key
    kb_unhighlightKey(keyId);

    // highlight next key
    var nextKey   = app_replaceSpecialChar(line.charAt(0));
    var nextKeyId = kb_getKeyIdFromKey(nextKey);
    kb_highlightKey(nextKeyId);

    // add correct keystroke to typometer
    tm_keystroke();

  } else { // wrong key, add to red text

    if(!app_inError) {

      app_errorCount++;
    }

    app_inError = true;

    kb_unhighlightKey(kb_lastHighlightedKeyId);
    kb_highlightKey(kb_getKeyIdFromKey("Backspace"));

    if(dp_red.length < 5) {

      // replace " " with "_" to make space visible
      var wrongChar = key === " " ? "_" : key;

      dp_setRedText(dp_red + wrongChar);
    }

  }

  ib_updateBar();   // update error count and error ratio
}

/**
  * handles special key presses like "Backspace"
  *
  * @param string key: the key that was pressed
  * @param string keyId: id of the key
  * @return boolean: is key a special key
  */
function app_handleSpecialKeyPress(key, keyId) {

  // if the key does not exists on keyboard layout, do nothing
  if(keyId === "None") return true;

  // if the key is a silent key, do nothing
  if(app_silentKeys.indexOf(key) !== -1) return true;

  switch(key) {

    case "Backspace":

      if(dp_red.length > 0) {  // there is wrong input

        // remove one character from red text
        dp_setRedText(dp_red.substring(0, dp_red.length - 1));

        // check if there is no more wrong input
        if(dp_red.length === 0) {

          app_inError = false;

          kb_unhighlightKey(kb_lastHighlightedKeyId);   // unhighlight backspace

          // highlight next key
          var nextKey = app_replaceSpecialChar(dp_normal.charAt(0));

          kb_highlightKey(kb_getKeyIdFromKey(nextKey));
        }
      }

      return true;

    default: return false;
  }

}

/**
  * replaces special key chars like Tab and Enter
  *
  * @param string key
  * @return string key
  */
function app_replaceSpecialChar(key) {

  switch(key) {

    case "Tab":     return "↹";
    case "Enter":   return "⏎";
    case "⏎":       return "Enter";
    case "↹":       return "Tab";
    default:        return key;
  }
}

/**
  * load first line and change app state to running
  */
function app_startLection() {

  // set text to line
  var line = sequence.lines[sequence.index];
  dp_setNormalText(line);

  // get first character and highlight corresponding key
  var nextKeyId = kb_getKeyIdFromKey(line.charAt(0));
  kb_highlightKey(nextKeyId);

  app_changeState(STATE_RUNNING);
}

/**
  * user has reached end of current line,
  * load next line and update progress bar
  */
function app_nextLine() {

  sequence.index++;

  kb_unhighlightKey(kb_lastHighlightedKeyId);

  if(sequence.index === sequence.lines.length) {  // there are no more lines

    // reset display and keyboard overlay
    dp_setGreenText("");
    dp_setRedText("");
    dp_setNormalText("");

    // change app state to finished
    app_changeState(STATE_FINISHED);
    return;
  }

  var line = sequence.lines[sequence.index];

  dp_setGreenText("");
  dp_setRedText("");
  dp_setNormalText(line);

  kb_highlightKey(kb_getKeyIdFromKey(line.charAt(0)));

  dp_lineSlideIn();
}


/**
  * upload results to server and redirect to page which shows results
  */
function app_uploadResults() {

  $.post("/results/upload",
  {
    nonce:      app_nonce,
    errors:     app_errorCount,
    velocity:   tm_velocity.toFixed(2),
    keystrokes: tm_keystrokes
  },
  function(data, status) {

    if(status == "success") {

      window.location = "/results/show";

    } else {

      console.error("Failed to upload results. Status: " + status);
    }
  });
}
/****
  *** @NOTE REQUIRED jQuery
  *** display:
  *** contains functions for manipulating the display
  *** can set normal, green and red text
  ***/

// display global variables
var dp_normal = "";     // the "normal" text (black on white)
var dp_red    = "";     // red text (for errors)
var dp_green  = "";     // green text (for correctly typed characters)

var dp_normalTextElement;
var dp_redTextElement;
var dp_greenTextElement;

/**
  * initialization
  */
function dp_init(loaded) {

  // init text elements
  dp_normalTextElement  = document.getElementById("normal");
  dp_redTextElement     = document.getElementById("red");
  dp_greenTextElement   = document.getElementById("green");

  loaded("display");
}

/**
  * set text for normal text element
  *
  * @param string text
  */
function dp_setNormalText(text) {

  dp_normal = text;
  dp_normalTextElement.textContent = dp_normal;
}

/**
  * set text for red text element
  *
  * @param string text
  */
function dp_setRedText(text) {

  dp_red = text;
  dp_redTextElement.textContent = dp_red;
}

/**
  * set text for green text element
  *
  * @param string text
  */
function dp_setGreenText(text) {

  dp_green = text;
  dp_greenTextElement.textContent = dp_green;
}

/**
  * slide animation (use for new line)
  */
function dp_lineSlideIn() {

  // add animation class
  $("#normal").addClass("slideRightIn");

  // remove animation class after 500 miliseconds
  setTimeout(function() {

    $("#normal").removeClass("slideRightIn");

  }, 500);
}
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

  loaded("infobar");
}

/**
  * update all text elements
  */
function ib_updateBar() {

  ib_velocityElement.html(tm_velocity.toFixed(1));
  ib_errorCountElement.html(app_errorCount);
  ib_errorRatioElement.html(( Math.min(app_errorCount / tm_keystrokes, 1) * 100.0).toFixed(1));
}
/****
  *** @NOTE requires jQuery
  *** @NOTE requires keyboard_builder.js
  *** keyboard actions:
  *** this file contains functions to manipulate the apperance of the keyboard
  ***/

var kb_lastHighlightedKeyId;    // the key id which was last used when kb_highlightKey() was called

/**
  * highlight all keys
  *
  * @return void
  */
function kb_highlightAllKeys() {

  for(var keyId in kb_keys) {

    kb_highlightKey(keyId);
  }
}

/**
  * unhighlight all keys
  *
  * @return void
  */
function kb_unhighlightAllKeys() {

  for(var keyId in kb_keys) {

    kb_unhighlightKey(keyId);
  }
}

/**
  * highlight a specific key
  *
  * @param string keyId: the id of the key (not the key name!)
  * @return void
  */
function kb_highlightKey(keyId) {

  kb_lastHighlightedKeyId = keyId;

  var key = kb_keys[keyId];

  // add highlight css class to key element
  $("#" + keyId).addClass(key.highlightClass);

  // draw connection from parent key to key
  var color = kb_getColorFromClass(key.highlightClass)
  kb_drawConnection(key.parentKeyId, keyId, color);
}

/**
  * unhighlight a specific key
  *
  * @param string keyId: the id of the key (not the key name!)
  * @return void
  */
function kb_unhighlightKey(keyId) {

  var key = kb_keys[keyId];

  // remove highlight css class to key element
  if(typeof key !== "undefined")
    $("#" + keyId).removeClass(key.highlightClass);

  kb_clearCanvas();
}

/**
  * activate (simulate pressing) a specific key
  *
  * @param string keyId: the id of the key (not the key name!)
  * @return void
  */
function kb_activateKey(keyId) {

  // add "activekey" to key's class
  $("#" + keyId).addClass("activekey");
}

/**
  * deactivate (simulate releading) a specific key
  *
  * @param string keyId: the id of the key (not the key name!)
  * @return void
  */
function kb_deactivateKey(keyId) {

  // remove "activekey" to key's class
  $("#" + keyId).removeClass("activekey");
}

/**
  * draw a line
  *
  * @param string fromKeyId
  * @param string toKeyId
  * @return void
  */
function kb_drawConnection(fromKeyId, toKeyId, color) {

  var fromKey = document.getElementById(fromKeyId);
  var toKey   = document.getElementById(toKeyId);

  var fromX = fromKey.offsetLeft  +   fromKey.clientWidth  /  2;
  var fromY = fromKey.offsetTop   +   fromKey.clientHeight /  2;
  var toX   = toKey.offsetLeft    +   toKey.clientWidth    /  2;
  var toY   = toKey.offsetTop     +   toKey.clientHeight   /  2;

  kb_canvas.strokeStyle   = color;
  kb_canvas.lineWidth     = kb_canvas_lineWidth;
  kb_canvas.globalAlpha   = kb_canvas_globalAlpha;

  kb_canvas.beginPath();
  kb_canvas.moveTo(fromX, fromY);
  kb_canvas.lineTo(toX, toY);
  kb_canvas.stroke();
}

/**
  * clear canvas
  *
  * @return void
  */
function kb_clearCanvas() {

  kb_canvas.clearRect(0, 0, kb_canvas.canvas.width, kb_canvas.canvas.width);
}

  /**
    * TODO
    * get color from highlight css class
    *
    * @param string cssClass
    * @return string color (hexcode)
    */
function kb_getColorFromClass(cssClass) {

  /* background-colors:
      (lighter):
      green:    #abd3bc;
      purple:   #c0b7d0;
      red:      #dcb9b6;
      yellow:   #ded2ad;

      (darker):
      green:    #00c853;
      purple:   #673ab7;
      red:      #f44336;
      yellow:   #ffc107;
  */

  switch(cssClass) {

    case "highlightGreen":    return "#00c853";
    case "highlightPurple":   return "#673ab7";
    case "highlightRed":      return "#f44336";
    case "highlightYellow":   return "#ffc107";

    default: return "#03a9f4";
  }
}
/****
  *** @NOTE requires jQuery
  *** keyboard builder:
  *** this file 'builds' the keyboard by loading the keyboard layout json file
  *** specified and creating the belonging HTML code.
  *** Also, this file is responsible for resizing the keyboard
  ***/

// keyboard objects
var kb_keys                     = [];       // holds every key accessible via key id (key: object consisting of label, parentKeyId and highlightClass)
var kb_canvas;                              // overlay, draws connections between keys

// maps: stores every key id, accessible via "key code" (the string returned by KeyEvent.key)
// (will be used in listener to get key id of pressed key, saves unnecessary long ifelse statements)
var kb_specialMap               = [];       // map of special keys (like shift, enter, …)
var kb_defaultMap               = [];       // map of normal key presses, without shift or altGr (like a, s, d, f, …)
var kb_shiftMap                 = [];       // map of keys pressed with shift (like A, S, D, F, …)
var kb_altGrMap                 = [];       // map of keys pressed with altGr (like @, }, …)

// keyboard properties (could change dynamically!)
var kb_layout                   = "";       // name of keyboard layout file (without extension! (always .json anyway))
var kb_locale                   = "";       // locale (which language, e.g. en, de, …)
var kb_width                    = 0;        // keyboard width
var kb_height                   = 0;        // keyboard height
var kb_ratio                    = 3;        // default width/height ratio of keyboard is 3 (could change for numpad keyboard)
var kb_canvas_lineWidth         = 2;        // line thickness of canvas
var kb_canvas_globalAlpha       = 0.8;      // how transparent the overlaying canvas is
var kb_isCapslock               = false;    // current capslock state

/**
  * load keyboad layout, create html elements, add final html code to DOM
  *
  * @param string layout: the layout id (usually the language, e.g. en-us, de-de...)
  * @return void
  */
function kb_init(loaded) {

  // load keyboard layout from json file
  $.get("/js/keyboard_layouts/" + kb_layout + ".json",

  function (data, status) {

    if(status == "success") {

      kb_locale = data.meta.locale;
      kb_ratio  = data.meta.ratio;

      var keyboardHTML = "";

      // loop through every element and prepare html code
      // eventually, add code to keyboardHTML
      for(var i = 0; i < data.keys.length; i++) {

        var key = data.keys[i];

        // required properties
        var id              = key.id;
        var label           = key.label;
        var left            = "left:" + key.left + "%;";
        var top             = "top:"  + key.top + "%;";
        var parentKeyId     = key.parentKeyId;
        var highlightClass  = key.highlightClass;

        // optional properties
        var classes = "key";    // css classes of html element
        var styles = "";        // style property of html element

        if(typeof key.classes !== "undefined") {        // check if property exists and add every single class to classes
          for(var k = 0; k < key.classes.length; k++) {
            classes += " " + key.classes[k];
          }
        }

        if(typeof key.style !== "undefined") {        // check if property exists and add to styles
          styles += key.style;
        }

        // add key to kb_keys
        kb_keys[id] = {
          label: label,
          parentKeyId: parentKeyId,
          highlightClass: highlightClass
        };

        // map keys
        // find out if the key is a special key
        if(typeof key.map.special !== "undefined") {  // is a special key (like shift,...)

          var location = typeof key.map.location !== "undefined" ? key.map.location : "";
          kb_specialMap[key.map.special + location] = id;

        } else {

          kb_defaultMap[key.map.default]  = id;
          kb_shiftMap[key.map.shift]      = id;
          kb_altGrMap[key.map.altGr]      = id;
        }

        // create final html element and add to keyboardHTML
        keyboardHTML += '<div id="'+id+'" class="'+classes+'" style="'+left+top+styles+'">'+label+'</div>';
      }

      // add canvas to bottom of html (so that overlay is actually OVER keys)
      keyboardHTML += '<canvas class="hud" id="hud" width="960" height="320"></canvas>';

      // add to DOM to make changes visible
      $('#keyboard').append(keyboardHTML);

      // init keyboard canvas
      kb_canvas               = document.getElementById("hud").getContext("2d");
      kb_canvas.lineWidth     = kb_canvas_lineWidth;
      kb_canvas.globalAlpha   = kb_canvas_globalAlpha;

      // set correct size
      kb_setSize();

      // everything ready, call callback
      loaded("keyboard");

    } else {

      console.error("Error while fetching keyboard layout. Returned status code: "+status);
    }
  });
}

/**
  * setSize() when window resizes and on init
  *
  * @return void
  */
$(window).resize(kb_setSize);

function kb_setSize() {

  kb_width  = $("#keyboard").width();
  kb_height = kb_width / kb_ratio;

  $("#keyboard").css("height", kb_height+"px");

  kb_canvas.canvas.width  = kb_width;
  kb_canvas.canvas.height = kb_height;
}
/****
  *** @NOTE requires jQuery
  *** @NOTE requires keyboard_builder.js
  *** @NOTE requires keyboard_actions.js
  *** @see https://developer.mozilla.org/en-US/docs/Web/API/KeyboardEvent
  *** keyboard listener:
  *** implements a keyboard listener
  ***/


/**
  * prevent default for various keys
  */
$(document).unbind('keydown').bind('keydown', function (e) {

  if( e.keyCode === 8           ||    // backspace
      e.keyCode === 9           ||    // tab
      e.keyCode === 32          ||    // space
      e.shiftKey                ||
      e.keyCode === 17          ||    // control
      e.keyCode === 18          ||    // alt
      e.key     === "Backspace" ||
      e.key     === "Tab"       ||
      e.key     === " "         ||
      e.key     === "Control"   ||
      e.key     === "Alt"
    ) {

    e.preventDefault();
  }
});

document.addEventListener('keydown',  kb_handleKeyDown);
document.addEventListener('keyup',    kb_handleKeyUp);

/**
  * key down handler
  *
  * @param KeyEvent e
  * @return string keyId
  */
function kb_handleKeyDown(e) {

  var keyId = kb_getKeyIdFromKeyEvent(e);

  if(keyId !== "CapsLock") {  // ignore capslock, fully handled by kb_handleKeyUp()

    // activate key on virtual keyboard
    kb_activateKey(keyId);

    // call app_keyPressed()
    app_keyPressed(e.key, keyId);
  }

  return keyId;
}

/**
  * key up handler
  *
  * @param KeyEvent e
  * @return string keyId
  */
function kb_handleKeyUp(e) {

  var keyId = kb_getKeyIdFromKeyEvent(e);

  if(keyId === "CapsLock") {

    kb_capslockPressed(kb_specialMap[keyId]);
    keyId = "None";   // reset keyId to prevent further interference

  } else {

    // deactivate key on virtual keyboard
    kb_deactivateKey(keyId);
  }

  return keyId;
}

/**
  * get key id from KeyEvent.key and KeyEvent.location
  *
  * @param KeyEvent
  * @return string keyId
  */
function kb_getKeyIdFromKeyEvent(e) {

  // find out key location if possible (i.e. "left", "right" or "")
  var location  =  kb_getKeyLocation(e)

  return kb_getKeyIdFromKey(e.key, location);
}

/**
  * get key id from KeyEvent.key or key character
  *
  * @param string key (i.e. "a", "S")
  * @param string location (i.e. "left", "right" or just "" (only for keys that appear twice on keyboard, like shift for example))
  * @return string keyId
  */
function kb_getKeyIdFromKey(key, location) {

  var keyId = "None";

  if(typeof location === "undefined") {
    location = "";
  }

  // search through maps to find key and corresponding key id

  if(key in kb_defaultMap) {       // "normal" key pressed

    keyId = kb_defaultMap[key];

  } else if(key in kb_shiftMap) {  // key with shift pressed

    keyId = kb_shiftMap[key];

  } else if(key in kb_altGrMap) {  // key with altGraph pressed

    keyId = kb_altGrMap[key];

  } else if((key+location) in kb_specialMap) {   // special key pressed

    if(key === "CapsLock") {

      keyId = key;

    } else {

      keyId = kb_specialMap[key + location];
    }


  } else if(key === " ") {        // special case for space key

    keyId = kb_specialMap["Space"];
  }

  return keyId;
}

/**
  * determine the location of a key
  * @NOTE can be undetermined, which means this function returns ""
  *
  * @param KeyEvent.location
  * @return string location ("left" | "right" | "")
  */
function kb_getKeyLocation(e) {

  var location = "";

  if (e.location === KeyboardEvent.DOM_KEY_LOCATION_LEFT) {

    location = "left";

  } else if (e.location === KeyboardEvent.DOM_KEY_LOCATION_RIGHT) {

    location = "right";
  }

  return location;
}


/**
  * handle press on capslock
  *
  * @param keyId (of capslock key)
  * @return void
  */
function kb_capslockPressed(keyId) {

  kb_isCapslock = ! kb_isCapslock;

  if(kb_isCapslock) {

    kb_activateKey(keyId);

  } else {

    kb_deactivateKey(keyId);
  }
}
/****
  *** @NOTE requires jQuery
  *** @NOTE requires keyboard_builder.js
  *** @NOTE requires keyboard_actions.js
  *** misc:
  *** miscellaneous functions needed for the main app
  ***/

// global vars
var misc_assignmentVisible  = false;
var misc_dividerVisible     = false;
var misc_keyboardVisible    = true;

/**
  * initialization
  */
function misc_init(loaded) {

  if(misc_assignmentVisible) {
    $("#assignment-state").removeClass("glyphicon-unchecked");
    $("#assignment-state").addClass("glyphicon-check");
  }

  if(misc_dividerVisible) {
    $("#divider-state").removeClass("glyphicon-unchecked");
    $("#divider-state").addClass("glyphicon-check");
  }

  if(misc_keyboardVisible) {
    $("#keyboard-state").removeClass("glyphicon-unchecked");
    $("#keyboard-state").addClass("glyphicon-check");
  }

  loaded("misc");
}

/**
  * onclick method in case user wants to cancel training
  */
function back() {

  $("#modal_back").modal("show");
}

/**
  * confirm going back (in #modal_back)
  * redirect user back to dashboard
  */
function backConfirm() {

  document.body.onbeforeunload = "";
  window.location.href = '/dashboard';
}

/**
  * shows/hides assignment of keys
  */
function showAssignment() {

  if(misc_assignmentVisible) {

    // update checkbox
    $("#assignment-state").addClass("glyphicon-unchecked");
    $("#assignment-state").removeClass("glyphicon-check");

    // update fingerInfo
    $("#fingerInfo").addClass("gone");

    // unhightlight keys
    kb_unhighlightAllKeys();

    misc_assignmentVisible = false;

  } else {

    // update checkbox
    $("#assignment-state").removeClass("glyphicon-unchecked");
    $("#assignment-state").addClass("glyphicon-check");

    // update fingerInfo
    $("#fingerInfo").removeClass("gone");

    // hightlight keys
    kb_highlightAllKeys();

    misc_assignmentVisible = true;
  }
}

/**
  * shows/hides divider between keys
  */
function showDivider() {

  if(misc_dividerVisible) {

    // update checkbox
    $("#divider-state").removeClass("glyphicon-check");
    $("#divider-state").addClass("glyphicon-unchecked");

    // hide divider
    $("#divider").addClass("gone");

    misc_dividerVisible = false;

  } else {

    // update checkbox
    $("#divider-state").addClass("glyphicon-check");
    $("#divider-state").removeClass("glyphicon-unchecked");

    // show divider
    $("#divider").removeClass("gone");

    misc_dividerVisible = true;
  }
}

/**
  * shows/hides keyboard
  */
function showKeyboard() {

  if(misc_keyboardVisible) {

    // update checkbox
    $("#keyboard-state").addClass("glyphicon-unchecked");
    $("#keyboard-state").removeClass("glyphicon-check");

    // hide keyboard
    kb_clearCanvas();
    $("#keyboard").addClass("gone");

    misc_keyboardVisible = false;

  } else {

    // update checkbox
    $("#keyboard-state").removeClass("glyphicon-unchecked");
    $("#keyboard-state").addClass("glyphicon-check");

    // show keyboard
    $("#keyboard").removeClass("gone");

    misc_keyboardVisible = true;
  }
}
/****
  *** @NOTE requires jQuery
  *** sequence:
  *** responsible for loading and preparing the sequence (lines of text to be typed by user)
  ***/

var sequence = {     // the object where the lines, the line pointer and the errors get stored
  index: 0,
  lines: [],
  errors: [],
};

// defaults
var sq_dataURI        = "";   // from which URI the sequence should be loaded
var sq_maxLineLength  = 19;   // max line length excluding appended ⏎

/**
  * initialization
  */
function sq_init(loaded) {

  // load sequence from server
  $.post(sq_dataURI, function(data, status) {

    if(status == "success") {

      app_nonce = data.meta.nonce;

      switch(data.meta.mode) {

        case "expand":            // adapt line content to fit display width (lines too short, use for single words)

          sq_expandLines(data.lines);
          break;

        case "block":             // "lines" are not prepared at all (lines too long)

          sq_sliceLines(data.lines);
          break;

        case "prepared":          // lines are already correctly prepared, store in sequence

          sq_prepareSequence(data.lines);
          break;

        default:

          console.error("Error in while preparing sequence: Unknown mode specified in sequence meta information");
          return;
      }

      loaded("sequence");

    } else {

      console.error("Failed to load sequence. Returned status code: " + status);
    }

  });

}

/**
  * repeat words to make lines longer
  *
  * @param Array lines
  */
function sq_expandLines(lines) {

  // TODO

  sq_prepareSequence(lines);
}

/**
  * slice between words to make lines fit to display width
  *
  * @param Array lines
  */
function sq_sliceLines(lines) {

  // TODO

  sq_prepareSequence(lines);
}

/**
  * prepare and store lines in sequence object
  *
  * @param Array lines
  */
function sq_prepareSequence(lines) {

  for(key in lines) {

    var line = lines[key];

    line = line.replace(/\n/g, "⏎");      // replace newlines with ⏎
    line = line.replace(/\t/g, "↹");      // replace tabs with ↹

    if(line.charAt(line.length) !== "⏎") {

      line += "⏎";
    }

    // store line in sequence object
    sequence.lines.push(line);
  }
}
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
