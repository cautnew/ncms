<?php

namespace Core\Route;

use Core\Route\Router;

class Route
{
  protected static $router;

  protected static function getRouter(): Router
  {
    if (empty(self::$router)) {
      self::$router = new Router;
    }

    return self::$router;
  }

  public static function add(string $method, string $pattern, $callback)
  {
    switch ($method) {
      case 'GET':
        return self::get($pattern, $callback);
      case 'POST':
        return self::post($pattern, $callback);
      case 'PUT':
        return self::put($pattern, $callback);
      case 'DELETE':
        return self::delete($pattern, $callback);
    }

    return self::get($pattern, $callback);
  }

  public static function get(string $pattern, $callback)
  {
    return self::getRouter()->get($pattern, $callback);
  }

  public static function post(string $pattern, $callback)
  {
    return self::getRouter()->post($pattern, $callback);
  }

  public static function put(string $pattern, $callback)
  {
    return self::getRouter()->put($pattern, $callback);
  }

  public static function delete(string $pattern, $callback)
  {
    return self::getRouter()->delete($pattern, $callback);
  }

  public static function resolve($pattern)
  {
    return self::getRouter()->resolve($pattern);
  }

  public static function translate($pattern, $params)
  {
    return self::getRouter()->translate($pattern, $params);
  }
}
