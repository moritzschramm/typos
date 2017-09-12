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
