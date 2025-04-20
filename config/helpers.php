<?php

use App\Models\NCMS\GlobalVars;

if (!function_exists('getGlobalVar')) {
  function getGlobalVar(string $key, $default = null)
  {
    return GlobalVars::findByName($key)->value ?? $default;
  }
}

if (!function_exists('getGlobalVarById')) {
  function getGlobalVarById(int $id, $default = null)
  {
    return GlobalVars::find($id)->value ?? $default;
  }
}

if (!function_exists('gv')) {
  function gv(string $name, $default = null)
  {
    return getGlobalVar($name, $default);
  }
}
