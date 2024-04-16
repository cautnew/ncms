<?php

namespace Core\Route;

use Core\Route\Exceptions\InvalidHttpStatusException;

class Response
{
  protected int $status;

  public function __construct()
  {}

  public function header()
  {}

  public function setStatus(int $status): self
  {
    if ($status < 100 || $status > 599) {
      $this->status = 200;

      throw new InvalidHttpStatusException("HTTP status code must be between 100 and 599. Status code '$status' provided.");
    }

    $this->status = $status;

		return $this;
  }

  public function getStatus(): int
  {
		if (!isset($this->status)) {
			$this->setStatus(200);
		}

		return $this->status;
  }

  public function status(?int $status = null): self | int
  {
		if ($status === null) {
			return $this->getStatus();
		}

		return $this->setStatus($status);
  }

  public function content()
  {}
}
