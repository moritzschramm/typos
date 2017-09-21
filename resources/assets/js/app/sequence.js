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

      if(typeof data.meta.resultURI !== "undefined") {        // check if property exists and add to styles
        app_resultURI = data.meta.resultURI;
      }

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
