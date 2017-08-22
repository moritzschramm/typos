/****
  *** @NOTE requires jQuery
  *** display:
  *** contains functions for manipulating the display
  ***/

// display global variables
var dp_normal = "";     // the "normal" text (black on white)
var dp_red    = "";     // red text (for errors)
var dp_green  = "";     // green text (for correctly typed characters)

var dp_normalTextElement;
var dp_redTextElement;
var dp_greenTextElement;

/**
  * initialization
  */
function dp_init() {

  // init text elements
  dp_normalTextElement  = document.getElementById("normal");
  dp_redTextElement     = document.getElementById("red");
  dp_greenTextElement   = document.getElementById("green");
}

/**
  * set text for normal text element
  *
  * @param string text
  */
function dp_setNormalText(text) {

  dp_normal = text;
  dp_normalTextElement.textContent = dp_normal;
}

/**
  * set text for red text element
  *
  * @param string text
  */
function dp_setRedText(text) {

  dp_red = text;
  dp_redTextElement.textContent = dp_red;
}

/**
  * set text for green text element
  *
  * @param string text
  */
function dp_setGreenText(text) {

  dp_green = text;
  dp_greenTextElement.textContent = dp_green;
}
