<?php

namespace Core\Support;

use Exception;
use Error;

class Session
{
  private string | null $prefixSessId = null;
  private $indAutoFlush = false;

  public function __construct(string $sessId = null)
  {
    if (!empty($sessId)) {
      $this->setSessId($sessId);
    }

    return $this;
  }

  public function __destruct()
  {
    $this->flush();
  }

  public function __get($param)
  {
    return $this->get($param);
  }

  public function __set($param, $value): void
  {
    $this->set($param, $value);
  }

  public function __isset($name)
  {
    return $this->has($name);
  }

  protected function isSessionActive(): bool
  {
    return in_array(session_status(), [PHP_SESSION_ACTIVE]);
  }

  public function setAutoFlush(bool $indAutoFlush = false): void
  {
    $this->indAutoFlush = $indAutoFlush;
  }

  public function getPrefixSessId(): ?string
  {
    return $this->prefixSessId;
  }

  public function setPrefixSessId($prefix): Session
  {
    $this->prefixSessId = $prefix;
    return $this;
  }

  public function getSessId(): ?string
  {
    $this->start();

    return session_id();
  }

  public function setSessId($sessId): Session
  {
    try {
      $this->stop();
      session_id($sessId);
    } catch (Exception $e) {
      return $this;
    } catch (Error $e) {
      return $this;
    }

    $this->sessId = $sessId;

    return $this;
  }

  public function createSessId(string $prefix = null): string
  {
    if (!empty($prefix)) {
      $this->setPrefixSessId($prefix);
    }

    return session_create_id($this->getPrefixSessId());
  }

  public function startNewSession(string $prefix = null): Session
  {
    $this->setSessId($this->createSessId($prefix));

    return $this;
  }

  public function start(): Session
  {
		if (!$this->isSessionActive() && !headers_sent()) {
			session_start();
		}

    return $this;
  }

  public function stop(): Session
  {
    if ($this->isSessionActive()) {
      session_write_close();
    }

    return $this;
  }

  public function destroy(): Session
  {
    if ($this->isSessionActive()) {
      $this->start();
      $this->clear();
      session_destroy();
    }

    return $this;
  }

  public function regenerate(): Session
  {
    session_regenerate_id(true);
    return $this;
  }

  public function all(): ?object
  {
    $this->start();

    return (object) $_SESSION;
  }

  public function clear(): void
  {
    $this->start();

    foreach ($this->all() as $k => $v) {
      unset($_SESSION[$k]);
    }

    if ($this->indAutoFlush) {
      $this->flush();
    }
  }

  public function flush(): void
  {
    session_write_close();
  }

  public function flash(): bool
  {
    if ($this->has("flash")) {
      $this->unset("flash");

      return true;
    }

    return false;
  }

  public function set(string $param, $value): Session
  {
    $this->start();

    $_SESSION[$param] = (is_array($value) ? (object)$value : $value);

    if ($this->indAutoFlush) {
      $this->flush();
    }

    return $this;
  }

  public function unset(string $key): Session
  {
    $this->start();

    unset($_SESSION[$key]);

    if ($this->indAutoFlush) {
      $this->flush();
    }

    return $this;
  }

  public function get(string $param)
  {
    if ($this->has($param)) {
      return $_SESSION[$param];
    }

    return null;
  }

  public function has(string $key): bool
  {
    $this->start();

    $this->flush();

    return isset($_SESSION[$key]);
  }

  public function csrf(): void
  {
    try {
      $randNum = random_bytes(20);
    } catch (\Exception $e) {
      $randNum = uniqid();
    }

    $_SESSION['csrf_token'] = base64_encode($randNum);
  }
}
