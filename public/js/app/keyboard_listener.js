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

  if( e.keyCode === 8   ||    // backspace
      e.keyCode === 9   ||    // tab
      e.keyCode === 32  ||    // space
      e.shiftKey        ||
      e.keyCode === 17  ||    // control
      e.keyCode === 18  ||    // alt
      e.key     === "Backspace" ||
      e.key     === "Tab"       ||
      e.key     === " "         ||
      e.key     === "Control"   ||
      e.key     === "Alt"
    ) {

    e.preventDefault();
  }
});

/**
  * key down listener
  */
document.addEventListener('keydown', function (e) {

  // find out location if possible
  var location = kb_getKeyLocation(e);

  // get key id matching key
  var keyId = kb_getKeyIdFromKey(e, location, "keydown");

  // activate key on virtual keyboard
  kb_activateKey(keyId);

});

/**
  * key up listener
  */
document.addEventListener('keyup', function (e) {

  // find out location if possible
  var location = kb_getKeyLocation(e);

  // get key id matching key
  var keyId = kb_getKeyIdFromKey(e, location, "keyup");

  // deactivate key on virtual keyboard
  kb_deactivateKey(keyId);
});


/**
  * get key id from KeyEvent.key and KeyEvent.location
  *
  * @param KeyEvent
  * @param location (i.e. "left", "right" or "")
  * @param string whichListenr (i.e. "keyup", "keydown")
  * @return string keyId
  */
function kb_getKeyIdFromKey(e, location, whichListener) {

  // search through maps to find key and corresponding key id
  var keyId = "None";

  if(e.key in kb_defaultMap) {       // "normal" key pressed

    keyId = kb_defaultMap[e.key];

  } else if(e.key in kb_shiftMap) {  // key with shift pressed

    keyId = kb_shiftMap[e.key];

  } else if(e.key in kb_altGrMap) {  // key with altGraph pressed

    keyId = kb_altGrMap[e.key];

  } else if((e.key+location) in kb_specialMap) {   // special key pressed

    if(e.key === "CapsLock") {

      keyId = "None";         // handle capslock separately
      if(whichListener === "keyup")
        kb_capslockPressed(kb_specialMap[e.key]);

    } else {

      keyId = kb_specialMap[e.key + location];
    }


  } else if(e.key === " ") {        // special case for space key

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
