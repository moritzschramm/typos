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
var misc_backURI            = "/dashboard";

/**
  * initialization
  */
function misc_init(loaded) {

  if(misc_keyboardVisible)   misc_showKeyboard();   else misc_hideKeyboard();
  if(misc_assignmentVisible) misc_showAssignment(); else misc_hideAssignment();
  if(misc_dividerVisible)    misc_showDivider();    else misc_hideDivider();

  loaded(app_modules.misc);
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
  window.location.href = misc_backURI;
}

/**
  * shows/hides assignment of keys
  */
function toggleAssignment() {

  if(misc_assignmentVisible) {

    misc_hideAssignment();

  } else {

    misc_showAssignment();
  }
  misc_assignmentVisible = ! misc_assignmentVisible;
}

/**
  * shows/hides divider between keys
  */
function toggleDivider() {

  if(misc_dividerVisible) {

    misc_hideDivider();

  } else {

    misc_showDivider();
  }
  misc_dividerVisible = ! misc_dividerVisible;
}

/**
  * shows/hides keyboard
  */
function toggleKeyboard() {

  if(misc_keyboardVisible) {

    misc_hideKeyboard();

  } else {

    misc_showKeyboard();
  }

  misc_keyboardVisible = ! misc_keyboardVisible;
}

function misc_showAssignment() {

  // update checkbox
  $("#assignment-state").removeClass("glyphicon-unchecked");
  $("#assignment-state").addClass("glyphicon-check");

  // update fingerInfo
  $("#fingerInfo").removeClass("gone");

  // hightlight keys
  kb_highlightAllKeys();
}

function misc_hideAssignment() {

  // update checkbox
  $("#assignment-state").addClass("glyphicon-unchecked");
  $("#assignment-state").removeClass("glyphicon-check");

  // update fingerInfo
  $("#fingerInfo").addClass("gone");

  // unhightlight keys
  kb_unhighlightAllKeys();
}

function misc_showDivider() {

  // update checkbox
  $("#divider-state").addClass("glyphicon-check");
  $("#divider-state").removeClass("glyphicon-unchecked");

  // show divider
  $("#divider").removeClass("gone");
}

function misc_hideDivider() {

  // update checkbox
  $("#divider-state").removeClass("glyphicon-check");
  $("#divider-state").addClass("glyphicon-unchecked");

  // hide divider
  $("#divider").addClass("gone");
}

function misc_showKeyboard() {

  // update checkbox
  $("#keyboard-state").removeClass("glyphicon-unchecked");
  $("#keyboard-state").addClass("glyphicon-check");

  // show keyboard
  $("#keyboard").removeClass("gone");
}

function misc_hideKeyboard() {

  // update checkbox
  $("#keyboard-state").addClass("glyphicon-unchecked");
  $("#keyboard-state").removeClass("glyphicon-check");

  // hide keyboard
  $("#keyboard").addClass("gone");
}
