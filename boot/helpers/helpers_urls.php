<?php

function parse_url_args($url_args)
{
  $arg = [];
  $arg_arr = explode('&', $url_args);

  if (empty($arg_arr)) {
    return null;
  }

  foreach ($arg_arr as $v) {
    $t = explode('=', $v);
    $arg[$t[0]] = $t[1];
  }

  return $arg;
}
