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
