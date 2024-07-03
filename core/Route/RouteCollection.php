<?php

namespace Core\Route;

use Exception;

class RouteCollection
{
  protected $routes_post = [];
  protected $routes_get = [];
  protected $routes_put = [];
  protected $routes_delete = [];
  protected $route_names = [];

  public function add($request_type, $pattern, $callback)
  {
    switch ($request_type) {
      case 'GET':
        return $this->addGet($pattern, $callback);
      case 'POST':
        return $this->addPost($pattern, $callback);
      case 'PUT':
        return $this->addPut($pattern, $callback);
      case 'DELETE':
        return $this->addDelete($pattern, $callback);
      default:
        throw new Exception("Request method ({$request_type}) not implemented.");
    }
  }

  public function where(string $request_type, string $pattern)
  {
    switch ($request_type) {
      case 'GET':
        return $this->findGet($pattern);
      case 'POST':
        return $this->findPost($pattern);
      case 'PUT':
        return $this->findPut($pattern);
      case 'DELETE':
        return $this->findDelete($pattern);
      default:
        throw new Exception("Request method ({$request_type}) not implemented.");
    }
  }

  protected function parseUri(string $uri)
  {
    return implode(DIRECTORY_SEPARATOR, array_filter(explode(DIRECTORY_SEPARATOR, $uri)));
  }

  protected function definePattern(string $pattern)
  {
    $pattern = implode(DIRECTORY_SEPARATOR, array_filter(explode(DIRECTORY_SEPARATOR, $pattern)));

    $pattern = '/^' . str_replace(DIRECTORY_SEPARATOR, '\/', $pattern) . '$/';

    if (preg_match("/\{[A-Za-z0-9\_\-]{1,}\}/", $pattern)) {
      $pattern = preg_replace("/\{[A-Za-z0-9\_\-]{1,}\}/", "[A-Za-z0-9\_\-]{1,}", $pattern);
    }

    return $pattern;
  }

  public function isThereAnyHow($name)
  {
    return $this->route_names[$name] ?? false;
  }

  protected function parsePattern(array $pattern)
  {
    $result['set'] =  $pattern['set'] ?? null;
    $result['as'] = $pattern['as'] ?? null;
    $result['namespace'] = $pattern['namespace'] ?? null;

    return $result;
  }

  public function getRouteNames()
  {
    return $this->route_names;
  }

  public function addGet($pattern, $callback): self
  {
    if (is_array($pattern)) {
      $settings = $this->parsePattern($pattern);
      $pattern = $settings['set'];
    } else {
      $settings = [];
    }

    $values = $this->toMap($pattern);

    $this->routes_get[$this->definePattern($pattern)] = [
      'callback' => $callback,
      'values' => $values,
      'namespace' => $settings['namespace'] ?? null
    ];

    if (isset($settings['as'])) {
      $this->route_names[$settings['as']] = $pattern;
    }

    return $this;
  }

  public function addPost($pattern, $callback): self
  {
    if (is_array($pattern)) {
      $settings = $this->parsePattern($pattern);
      $pattern = $settings['set'];
    } else {
      $settings = [];
    }

    $values = $this->toMap($pattern);

    $this->routes_post[$this->definePattern($pattern)] = [
      'callback' => $callback,
      'values' => $values,
      'namespace' => $settings['namespace'] ?? null
    ];

    if (isset($settings['as'])) {
      $this->route_names[$settings['as']] = $pattern;
    }

    return $this;
  }

  public function addPut($pattern, $callback): self
  {
    if (is_array($pattern)) {
      $settings = $this->parsePattern($pattern);
      $pattern = $settings['set'];
    } else {
      $settings = [];
    }

    $values = $this->toMap($pattern);

    $this->routes_put[$this->definePattern($pattern)] = [
      'callback' => $callback,
      'values' => $values,
      'namespace' => $settings['namespace'] ?? null
    ];

    if (isset($settings['as'])) {
      $this->route_names[$settings['as']] = $pattern;
    }

    return $this;
  }

  public function addDelete($pattern, $callback): self
  {
    if (is_array($pattern)) {
      $settings = $this->parsePattern($pattern);
      $pattern = $settings['set'];
    } else {
      $settings = [];
    }

    $values = $this->toMap($pattern);

    $this->routes_delete[$this->definePattern($pattern)] = [
      'callback' => $callback,
      'values' => $values,
      'namespace' => $settings['namespace'] ?? null
    ];

    if (isset($settings['as'])) {
      $this->route_names[$settings['as']] = $pattern;
    }

    return $this;
  }

  protected function findPost($pattern_sent)
  {
    $pattern_sent = $this->parseUri($pattern_sent);

    foreach ($this->routes_post as $pattern => $callback) {
      if (preg_match($pattern, $pattern_sent, $pieces)) {
        return (object) ['callback' => $callback, 'uri' => $pieces];
      }
    }

    return false;
  }

  protected function findGet($pattern_sent)
  {
    $pattern_sent = $this->parseUri($pattern_sent);

    foreach ($this->routes_get as $pattern => $callback) {
      if (preg_match($pattern, $pattern_sent, $pieces)) {
        return (object) ['callback' => $callback, 'uri' => $pieces];
      }
    }

    return false;
  }

  protected function findPut($pattern_sent)
  {
    $pattern_sent = $this->parseUri($pattern_sent);

    foreach ($this->routes_put as $pattern => $callback) {
      if (preg_match($pattern, $pattern_sent, $pieces)) {
        return (object) ['callback' => $callback, 'uri' => $pieces];
      }
    }

    return false;
  }

  protected function findDelete($pattern_sent)
  {
    $pattern_sent = $this->parseUri($pattern_sent);

    foreach ($this->routes_delete as $pattern => $callback) {
      if (preg_match($pattern, $pattern_sent, $pieces)) {
        return (object) ['callback' => $callback, 'uri' => $pieces];
      }
    }

    return false;
  }

  protected function strposarray(string $haystack, array $needles, int $offset = 0)
  {
    if (strlen($haystack) > 0 && count($needles) > 0) {
      foreach ($needles as $element) {
        $position = strpos($haystack, $element, $offset);
        if ($position !== false) {
          break;
        }
      }
    }

    return $position ?? false;
  }

  protected function toMap($pattern)
  {
    $result = [];
    $needles = ['{', '[', '(', "\\"];
    $pattern = array_filter(explode('/', $pattern));

    foreach ($pattern as $key => $element) {
      $found = $this->strposarray($element, $needles);

      if ($found !== false) {
        if (substr($element, 0, 1) === '{') {
          $index = preg_filter('/([\{\}])/', '', $element);
          $result[$index] = $key;
        } else {
          $index = 'value_' . !empty($result) ? count($result) + 1 : 1;
          array_merge($result, [$index => $key]);
        }
      }
    }

    return count($result) > 0 ? $result : false;
  }

  public function convert($pattern, $params = null)
  {
    if (!is_array($params)) {
      $params = array($params);
    }

    $positions = $this->toMap($pattern);
    if ($positions == false) {
      $positions = [];
    }

    $pattern = array_filter(explode('/', $pattern));

    if (count($positions) < count($pattern)) {
      $uri = [];
      foreach ($pattern as $key => $element) {
        if (in_array($key - 1, $positions)) {
          $uri[] = array_shift($params);
        } else {
          $uri[] = $element;
        }
      }

      return implode('/', array_filter($uri));
    }

    return false;
  }
}
