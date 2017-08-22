/****
  *** @NOTE requires jQuery
  *** @NOTE requires keyboard_builder.js
  *** keyboard actions:
  *** this file contains functions to manipulate the apperance of the keyboard
  ***/

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

  var key = kb_keys[keyId];

  // add highlight css class to key element
  $("#" + keyId).addClass(key.highlightClass);

  // draw connection from parent key to key
  var color = kb_getColorFromClass(key.highlightClass)
  kb_drawConnection(kb_keys[key.parentKeyId], key, color);
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

  // after kb_pressAnimationDuration, remove again from class
  /*setTimeout(function() {

    $("#" + keyId).removeClass("activekey");

  }, kb_pressAnimationDuration);*/
}

/**
  * activate (simulate pressing) a specific key
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
  * @param string fromKey
  * @param string toKey
  * @return void
  */
function kb_drawConnection(fromKey, toKey, color) {

  var fromX = fromKey.offsetLeft  +   fromKey.clientWidth  /  2;
  var fromY = fromKey.offsetTop   +   fromKey.clientHeight /  2;
  var toX   = toKey.offsetLeft    +   toKey.clientWidth    /  2;
  var toY   = toKey.offsetTop     +   toKey.clientHeight   /  2;

  kb_canvas.strokeStyle = color;

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

  return '#03a9f4';
  /* background-colors:
    green: abd3bc;
    purple: c0b7d0;
    red: dcb9b6;
    yellow: ded2ad;
  */
}
