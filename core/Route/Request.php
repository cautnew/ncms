<?php

namespace Core\Route;

class Request
{
  protected $files;
  protected $base;
  protected $uri;
  protected $method;
  protected $protocol;
  protected $data = [];

  public function __construct()
  {
    $this->base = $_SERVER['REQUEST_URI'];
    // $this->uri  = $_REQUEST['route'] ?? '/';
    $this->uri  = $_SERVER['REQUEST_URI'] ?? '/';
    $this->method = $this->getMethod();
    $this->protocol = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
    $this->setData();
  }

  protected function setData()
  {
    switch ($this->getMethod()) {
      case 'POST':
        $this->data = $_POST;
        break;
      case 'GET':
        $this->data = $_GET;
        break;
      case 'INPUT':
        $json = file_get_contents('php://input');
        $this->data = json_decode($json, true) ?? [];
        break;
      case 'HEAD':
      case 'PUT':
      case 'DELETE':
      case 'OPTIONS':
        $valuestr = file_get_contents('php://input');
        $this->data = json_decode($valuestr, true) ?? [];
    }

    foreach ($_FILES as $key => $value) {
      $this->files[$key] = $value;
    }
  }

  public function base()
  {
    return $this->base;
  }

  public function method()
  {
    return $this->method;
  }

  public function getMethod(): ?string
  {
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? null);
  }

  public function uri()
  {
    return $this->uri;
  }

  public function all()
  {
    return $this->data;
  }

  public function __isset($key)
  {
    return isset($this->data[$key]);
  }

  public function __get($key)
  {
    if (isset($this->data[$key])) {
      return $this->data[$key];
    }
  }

  public function hasFile($key): bool
  {
    return isset($this->files[$key]);
  }

  public function file($key)
  {
    if (isset($this->files[$key])) {
      return $this->files[$key];
    }

    return null;
  }
}
