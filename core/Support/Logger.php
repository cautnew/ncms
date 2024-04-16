<?php

namespace Core\Support;

use Core\Support\Log;

class Logger {
  private static Log $log;

  public static function getLog(): Log {
    if (!isset(self::$log)) {
      self::$log = new Log();
    }

    return self::$log;
  }

  public static function regException(\Exception $e, string | null $desc = null): Log
  {
    return self::getLog()->regException($e, $desc);
  }

  public static function regError(string | \Exception $txt): Log
  {
    return self::getLog()->regError($txt);
  }

  public static function error(string | \Exception $txt): Log
  {
    return self::getLog()->error($txt);
  }

  public static function regErro(string | \Exception $txt): Log
  {
    return self::getLog()->regErro($txt);
  }

  public static function erro(string | \Exception $txt): Log
  {
    return self::getLog()->erro($txt);
  }

  public static function regWarning(string | \Exception $txt): Log
  {
    return self::getLog()->regWarning($txt);
  }

  public static function warning(string | \Exception $txt): Log
  {
    return self::getLog()->warning($txt);
  }

  public static function regAviso(string | \Exception $txt): Log
  {
    return self::getLog()->regAviso($txt);
  }

  public static function aviso(string | \Exception $txt): Log
  {
    return self::getLog()->aviso($txt);
  }

  public static function regLog(string | \Exception $txt): Log
  {
    return self::getLog()->regLog($txt);
  }

  public static function reg(string | \Exception $txt): Log
  {
    return self::getLog()->reg($txt);
  }

  public static function regInfo(string | \Exception $txt): Log
  {
    return self::getLog()->regInfo($txt);
  }

  public static function info(string | \Exception $txt): Log
  {
    return self::getLog()->info($txt);
  }

  public static function regDanger(string | \Exception $txt): Log
  {
    return self::getLog()->regDanger($txt);
  }

  public static function regSuccess(string | \Exception $txt): Log
  {
    return self::getLog()->regSuccess($txt);
  }

  public static function success(string | \Exception $txt): Log
  {
    return self::getLog()->success($txt);
  }
}
