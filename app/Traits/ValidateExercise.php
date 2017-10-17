<?php

namespace App\Traits;

trait ValidateExercise {

  /**
    * checks if exercise contains any invalid characters
    *
    * @param string $content
    * @return null|string $invalidChars
    */
  public function findInvalidCharacters($content)
  {
    $allowed = '\.ß+#*~-_,;:!"\'?§$%&()[]{}°<>|@€µ=äöü';
    $allowed_already_escaped = '\/ ';   # whitespace is intended
    $always_allowed = 'a-zA-Z0-9\r\n\t';
    $regex = '/(*UTF8)[^' . $always_allowed . $allowed_already_escaped . preg_quote($allowed) . ']/';
    # the (*UTF8) is necessary because it's 2017 and php still sucks _abysmally_ at unicode
    # @see https://stackoverflow.com/questions/1725227/preg-match-and-utf-8-in-php


    $match_counter = preg_match_all($regex, $content, $matches);

    return $match_counter === 0 ? null : implode($matches[0]);
  }
}
