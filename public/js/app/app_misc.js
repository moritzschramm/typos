/****
  *** @NOTE requires jQuery
  *** @NOTE requires keyboard_builder.js
  *** @NOTE requires keyboard_actions.js
  *** app_misc:
  *** miscellaneous functions needed for the main app
  ***/

// global vars
var misc_assignmentVisible  = false;
var misc_dividerVisible     = false;
var misc_keyboardVisible    = true;

/**
  * init function
  * @NOTE this should be called after everything has been initialized (keyboard, display etc)
  */
function app_misc_init() {

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
}

/**
 * onclick method in case user wants to cancel training
 */
function back() {

  $("#modal_back").modal("show");
}
/**
 * confirmation function for back modal
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
