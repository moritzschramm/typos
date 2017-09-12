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
