/****
  *** @NOTE requires jQuery
  *** sequence:
  *** responsible for loading and preparing the sequence (lines of text to be typed by user)
  ***/

var sequence = {     // the object where the lines, the line pointer and the errors get stored
  index:    0,
  lines:    [],
  errors:   [],
};

// defaults
var sq_dataURI        = "";   // from which URI the sequence should be loaded (set in training view)
var sq_maxLineLength  = 20;   // max line length excluding appended ⏎

/**
  * initialization
  */
function sq_init(loaded) {

  // load sequence from server
  $.post(sq_dataURI, function(data, status) {

    if(status == "success") {

      if(is_set(data.meta)) {

        var m = data.meta;

        app_uploadResultURI = is_set(m.uploadResultURI) ? m.uploadResultURI : app_uploadResultURI;
        app_showResultURI   = is_set(m.showResultURI)   ? m.showResultURI   : app_showResultURI;

      }

      sq_prepareSequence(data.lines);

      loaded(app_modules.sequence);

    } else {

      console.error("Failed to load sequence. Returned status code: " + status);
    }

  });

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
    line = line.replace(/ /g, "␣");

    // store line in sequence object
    sequence.lines.push(line);
  }
}

/**
  * helper function to check if a property is set
  */
function is_set(property) {

  return (typeof property !== "undefined");
}
