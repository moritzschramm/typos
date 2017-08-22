/****
  *** @NOTE requires jQuery
  *** keyboard builder:
  *** this file 'builds' the keyboard by loading the keyboard layout json file
  *** specified and creating the belonging HTML code.
  *** Also, this file is responsible for resizing the keyboard
  ***/

// keyboard objects
var kb_keys                     = [];
var kb_specialMap               = [];
var kb_defaultMap               = [];
var kb_shiftMap                 = [];
var kb_altGrMap                 = [];
var kb_canvas;

// keyboard properties (could change dynamically!)
var kb_locale                   = "";
var kb_width                    = 0;
var kb_height                   = 0;
var kb_ratio                    = 3;        // default width/height ratio is 3
var kb_canvas_lineWidth         = 5;
var kb_canvas_globalAlpha       = 0.9;
var kb_isCapslock               = false;

/**
  * load keyboad layout, create html elements, add final html code to DOM
  *
  * @param string layout: the layout id (usually the language, e.g. en-us, de-de...)
  * @return void
  */
function kb_init(layout) {

  // init keyboard canvas
  kb_canvas               = document.getElementById("hud").getContext("2d");
  kb_canvas.lineWidth     = kb_canvas_lineWidth;
  kb_canvas.globalAlpha   = kb_canvas_globalAlpha;

  // load keyboard layout from json file
  $.get("/js/keyboard_layouts/" + layout + ".json",

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
        var classes = "key";
        var styles = "";

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

      // add to DOM and init size to make changes visible
      $('#keyboard').append(keyboardHTML);
      kb_setSize();

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
$(window).resize(function() {  kb_setSize();  });
function kb_setSize() {

  kb_width  = $("#keyboard").width();
  kb_height = kb_width / kb_ratio;

  $("#keyboard").css("height", kb_height+"px");

  kb_canvas.canvas.width  = kb_width;
  kb_canvas.canvas.height = kb_height;
}
