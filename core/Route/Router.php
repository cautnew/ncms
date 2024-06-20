<?php

namespace Core\Route;

use Boot\Autoload;
use Boot\Constants\DirConstant as DC;
use Core\Route\Dispacher;
use Core\Route\RouteCollection;

class Router
{
  protected $route_collection;
  protected $dispacher;

  public function getRouteCollection(): RouteCollection
  {
    if (!isset($this->route_collection)) {
      $this->setRouteCollection(new RouteCollection);
    }

    return $this->route_collection;
  }

  public function setRouteCollection(RouteCollection $route_collection): self
  {
    $this->route_collection = $route_collection;

    return $this;
  }

  public function getDispacher(): Dispacher
  {
    if (!isset($this->dispacher)) {
      $this->setDispacher(new Dispacher);
    }

    return $this->dispacher;
  }

  public function setDispacher(Dispacher $dispacher): self
  {
    $this->dispacher = $dispacher;

    return $this;
  }

  public function get($pattern, $callback)
  {
    $this->getRouteCollection()->addGet($pattern, $callback);
    return $this;
  }

  public function post($pattern, $callback)
  {
    $this->getRouteCollection()->addPost($pattern, $callback);
    return $this;
  }

  public function put($pattern, $callback)
  {
    $this->getRouteCollection()->addPut($pattern, $callback);
    return $this;
  }

  public function delete($pattern, $callback)
  {
    $this->getRouteCollection()->addDelete($pattern, $callback);
    return $this;
  }

  public function find($request_type, $pattern)
  {
    return $this->getRouteCollection()->where($request_type, $pattern);
  }

  protected function dispach($route, $params, $namespace = "")
  {
    return $this->getDispacher()->dispach($route->callback, $params, $namespace);
  }

  public function notFound(bool $isAPI = false): void
  {
    header("HTTP/1.0 404 Not Found", true, 404);
    if ($isAPI) {
      Autoload::setToJsonResponse();
      echo json_encode([
        'erro' => [
          'cod' => '404',
          'txt' => 'Route not found.'
        ]
      ]);

      return;
    }

    echo require_once DC::PSOURCE . '/defresponse/cod-404.php';
  }

  public function notAllowed(bool $isAPI = false): void
  {
    header("HTTP/1.0 403 Forbidden", true, 403);
    if ($isAPI) {
      Autoload::setToJsonResponse();
      echo json_encode([
        'erro' => [
          'cod' => '403',
          'txt' => "You don't have permission to access this resource."
        ]
      ]);

      return;
    }

    echo require_once DC::PSOURCE . '/defresponse/cod-401.php';
  }

  public function notAutenticated(bool $isAPI = false): void
  {
    header("HTTP/1.0 401 Unauthorized", true, 401);
    if ($isAPI) {
      Autoload::setToJsonResponse();
      echo json_encode([
        'erro' => [
          'cod' => '401',
          'txt' => 'You must be authenticated to access this resource.'
        ]
      ]);

      return;
    }

    echo require_once DC::PSOURCE . '/defresponse/cod-401.php';
  }

  public function resolve(Request $request)
  {
    $route_collection = $this->find($request->method(), $request->uri());

    if ($route_collection) {
      $params = $route_collection->callback['values'] ? $this->getValues($request->uri(), $route_collection->callback['values']) : [];
      return $this->dispach($route_collection, $params);
    }

    return $this->notFound();
  }

  protected function getValues($pattern, $positions)
  {
    $result = [];

    $pattern = array_filter(explode('/', $pattern));

    foreach ($pattern as $key => $value) {
      if (in_array($key, $positions)) {
        $result[array_search($key, $positions)] = $value;
      }
    }

    return $result;
  }

  public function translate($name, $params)
  {
    $pattern = $this->getRouteCollection()->isThereAnyHow($name);

    if ($pattern) {
      $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
      $server = $_SERVER['SERVER_NAME'] . '/';
      $uri = [];

      foreach (array_filter(explode('/', $_SERVER['REQUEST_URI'])) as $key => $value) {
        if ($value == 'public') {
          $uri[] = $value;
          break;
        }
        $uri[] = $value;
      }
      $uri = implode('/', array_filter($uri)) . '/';

      return $protocol . $server . $uri . $this->getRouteCollection()->convert($pattern, $params);
    }

    return false;
  }
}
