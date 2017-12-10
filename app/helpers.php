<?php

/***
  ** HELPER FILE
  ** ALL METHODS IN THIS FILE ARE GLOBALLY AVAILABLE
  **/

/**
  * creates cryptographically secure tokens
  * NOTE: if the length is uneven, the token will get
  *       truncated (last char) to match requested length
  *
  * @param $length: the length of the string
  * @return string: the generated string
  */
function generateSecureString($length = 16)
{

  $sec = bin2hex(random_bytes(ceil($length / 2)));

  if(strlen($sec) - 1 === $length) {

    $sec = substr($sec, 0, -1);
  }

  return $sec;
}

/**
 * Return a UUID (version 4) using random bytes
 * Note that version 4 follows the format:
 *     xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx
 * where y is one of: [8, 9, A, B]
 *
 * We use (random_bytes(1) & 0x0F) | 0x40 to force
 * the first character of hex value to always be 4
 * in the appropriate position.
 *
 * For 4: http://3v4l.org/q2JN9
 * For Y: http://3v4l.org/EsGSU
 * For the whole shebang: http://3v4l.org/BkjBc
 *
 * @link https://paragonie.com/b/JvICXzh_jhLyt4y3
 *
 * @return string
 */
function uuidv4()
{
    return implode('-', [
        bin2hex(random_bytes(4)),
        bin2hex(random_bytes(2)),
        bin2hex(chr((ord(random_bytes(1)) & 0x0F) | 0x40)) . bin2hex(random_bytes(1)),
        bin2hex(chr((ord(random_bytes(1)) & 0x3F) | 0x80)) . bin2hex(random_bytes(1)),
        bin2hex(random_bytes(6))
    ]);
}

/**
  * Retrieve hash of current commit
  * @return string hash
  */
function build_number() {

  $build = `git rev-parse --short HEAD`;
  return $build ? $build : '';
}
