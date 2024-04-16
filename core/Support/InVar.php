<?php

namespace Core\Support;

use Core\Support\Session;

class InVar
{
  private $ses;
  private array $data = [];
  private array $input;
  private $credential;
  private int $filter = FILTER_DEFAULT;

  public function __construct(string $hash = null)
  {
    $this->ses = new Session();

    if (!empty($hash)) {
      $this->setFromHash($hash);
    }

    $json = file_get_contents('php://input');
    $this->input = json_decode($json, true) ?? [];
  }

  public function __get($key): ?string
  {
    return $this->get($key);
  }

  public function __set(string $key, $value)
  {
    $this->data[$key] = $value;
  }

  public function __isset(string $key): bool
  {
    return $this->has($key);
  }

  public function __unset(string $key)
  {
    $this->data[$key] = NULL;
    $_GET[$key] = NULL;
    $_POST[$key] = NULL;
    $this->ses->$key = NULL;
    $_FILES[$key] = NULL;
  }

  public function setFilter(int $filter): InVar
  {
    $this->filter = $filter;
    return $this;
  }

  public function get(string $key): ?string
  {
    if (!empty($this->data[$key])) {
      return filter_var($this->data[$key], $this->filter);
    }

    if (!empty($this->input[$key])) {
      return filter_var($this->input[$key], $this->filter);
    }

    if (!empty($_GET[$key])) {
      return filter_input(INPUT_GET, $key, $this->filter);
    }

    if (!empty($_POST[$key])) {
      return filter_input(INPUT_POST, $key, $this->filter);
    }

    if ($this->ses->has($key)) {
      return filter_var($this->ses->$key, $this->filter);
    }

    if (!empty($_FILES[$key])) {
      return $_FILES[$key];
    }

    return NULL;
  }

  public function getGet(): array
  {
    return $_GET;
  }

  public function getPost(): array
  {
    return $_POST;
  }

  public function getInput(): array
  {
    return $this->input;
  }

  public function getData(): array
  {
    return $this->data;
  }

  public function getFiles(): array
  {
    return $_FILES;
  }

  public function has(string $key): bool
  {
    return array_key_exists($key, $this->data) || array_key_exists($key, $this->input) || array_key_exists($key, $_GET);
  }

  public function empty(string $key): bool
  {
    if (!$this->has($key)) {
      return false;
    }

    $val = trim($this->get($key) ?? '');

    return empty($val);
  }

  public function ifnull(string $key, $valIfNull)
  {
    $val = $this->get($key);
    return $val ?? $valIfNull;
  }

  public function loadCredentials(): self
  {
    $this->credential['COD_USUARIO'] = $this->ses->COD_USUARIO;
    $this->credential['COD_PESSOA'] = $this->ses->COD_PESSOA;
    $this->credential['COD_COLABORADOR'] = $this->ses->COD_COLABORADOR;
    $this->credential['LOGIN_REDE'] = $this->ses->LOGIN_REDE;
    $this->credential['COD_REGIONAL'] = $this->ses->COD_REGIONAL;
    $this->credential['COD_NIVEL'] = $this->ses->COD_NIVEL;

    return $this;
  }

  public function getHash(bool $all = false): ?string
  {
    $arrayArgs = [
      'data' => $this->data,
      'credential' => $this->credential
    ];

    if (!$all) {
      return encrypt_args($arrayArgs);
    }

		foreach ($_GET as $k => $v) {
			$arrayArgs['data']["GET_{$k}"] = $v;
		}

		foreach ($_POST as $k => $v) {
			$arrayArgs['data']["POST_{$k}"] = $v;
		}

		foreach ($_FILES as $k => $v) {
			$arrayArgs['data']["FILES_{$k}"] = $v;
		}

    return encrypt_args($arrayArgs);
  }

  public function setSession(Session $ses): self
  {
    $this->ses = $ses;

    return $this;
  }

  public function setFromHash(string $hash): InVar
  {
    $decrypt = decrypt_args($hash);
    $this->data = $decrypt['data'];
    $this->credential = $decrypt['credential'] ?? [];

    return $this;
  }

  public function getMethodGet(): array
  {
    return $_GET;
  }

  public function getMethodPost(): array
  {
    return $_POST;
  }

  public function getMethodInput(): array
  {
    return $this->input;
  }

  public function getMethod(): ?string
  {
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? null);
  }
}
